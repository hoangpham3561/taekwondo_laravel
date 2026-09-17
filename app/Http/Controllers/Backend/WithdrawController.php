<?php

namespace App\Http\Controllers\Backend;

use App\Models\TransGdRequirement;
use App\Models\TransGdRequirementsDetail;
use App\Models\User;
use App\Http\Requests\Backend\Withdraw\ApproveWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ImageUpload;
use App\Traits\Telegram;

class WithdrawController extends BaseController
{
    use ImageUpload, Telegram;

    private $pathViewController = 'pages.backend.withdraw.';

    /**
     * Display a listing of withdraw requests
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Chỉ lấy các yêu cầu rút tiền (VIP = 'WITHDRAW')
        $query = TransGdRequirement::with(['user', 'detail'])
            ->where('VIP', 'WITHDRAW')
            ->orderBy('RequestDate', 'desc');

        // Tìm kiếm theo UserName/Email/FullName
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('UserName', 'like', '%' . $search . '%')
                    ->orWhere('Email', 'like', '%' . $search . '%')
                    ->orWhere('FullName', 'like', '%' . $search . '%');
            });
        }

        // Tìm kiếm theo SerialCode
        if ($request->has('serial_code') && !empty($request->serial_code)) {
            $query->where('SerialCode', 'like', '%' . $request->serial_code . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('RequestStatus', $request->status);
        }

        // Lọc theo loại ví (Currency)
        if ($request->has('currency') && !empty($request->currency)) {
            $query->where('Currency', $request->currency);
        }

        // Lọc theo khoảng ngày
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('RequestDate', '>=', $request->from_date);
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('RequestDate', '<=', $request->to_date);
        }

        // Phân trang
        $withdraws = $query->paginate(50)->withQueryString();

        // Thống kê tổng hợp
        $totalWithdraw = TransGdRequirement::where('VIP', 'WITHDRAW')
            ->where('RequestStatus', 'Y')
            ->sum('AmountTransfer');

        $totalPending = TransGdRequirement::where('VIP', 'WITHDRAW')
            ->where('RequestStatus', 'N')
            ->count();

        $totalCount = TransGdRequirement::where('VIP', 'WITHDRAW')
            ->count();

        return view($this->pathViewController . 'index', [
            'withdraws' => $withdraws,
            'totalWithdraw' => $totalWithdraw ?? 0,
            'totalPending' => $totalPending ?? 0,
            'totalCount' => $totalCount ?? 0,
            'search' => $request->search ?? '',
            'serialCode' => $request->serial_code ?? '',
            'status' => $request->status ?? '',
            'currency' => $request->currency ?? '',
            'fromDate' => $request->from_date ?? '',
            'toDate' => $request->to_date ?? '',
        ]);
    }

    /**
     * Duyệt yêu cầu rút tiền
     *
     * @param ApproveWithdrawRequest $request
     * @param int $id RequestID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(ApproveWithdrawRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $withdraw = TransGdRequirement::with(['user', 'detail'])->findOrFail($id);

            // Kiểm tra trạng thái hiện tại
            if ($withdraw->RequestStatus != 'N') {
                return redirect()->back()
                    ->with('error', 'Yêu cầu này đã được xử lý. Không thể duyệt lại.');
            }

            // Upload ảnh hóa đơn chuyển khoản lên API và lấy link
            $receiptImageLink = '';
            if ($request->hasFile('receipt_image')) {
                $receiptImageLink = $this->getLinkImage($request->file('receipt_image'));

                if (empty($receiptImageLink)) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Không thể upload ảnh hóa đơn. Vui lòng thử lại.');
                }
            }

            // Cập nhật trạng thái thành đã duyệt
            $withdraw->update([
                'RequestStatus' => 'Y',
                'Status' => 'Y',
            ]);

            if ($withdraw->detail) {
                $withdraw->detail->update([
                    'BTCImage' => $receiptImageLink,
                    'DateEnd' => now(),
                    'Status' => 'Y',
                ]);
            }

            // Gửi thông báo Telegram
            $admin = auth()->guard('admin')->user();
            $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

            $tgMessage = "✅ <b>DUYỆT RÚT TIỀN THÀNH CÔNG</b>\n\n";
            $tgMessage .= "Admin: <b>{$adminName}</b>\n";
            $tgMessage .= "Mã GD: <code>{$withdraw->SerialCode}</code>\n";
            $tgMessage .= "User: {$withdraw->user->UserName} (ID: {$withdraw->user->UserID})\n";
            $tgMessage .= "Số tiền: " . number_format($withdraw->detail->PV, 0, ',', '.') . " VND\n";
            $tgMessage .= "Số TK: {$withdraw->detail->BTCAddress}\n";
            $tgMessage .= "Ngân hàng: {$withdraw->detail->BankName}\n";
            $tgMessage .= "Chủ TK: {$withdraw->detail->FullName}\n";
            $tgMessage .= "Hóa đơn: {$receiptImageLink}\n";
            $tgMessage .= "Thời gian: " . now()->format('d/m/Y H:i:s');
            $this->sendMesssageTelegram($tgMessage, 'withdraw');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Duyệt rút tiền thành công! Mã GD: ' . $withdraw->SerialCode);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve withdraw error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi duyệt rút tiền: ' . $e->getMessage());
        }
    }

    /**
     * Hủy yêu cầu rút tiền (trả tiền lại cho user)
     *
     * @param Request $request
     * @param int $id RequestID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $withdraw = TransGdRequirement::with(['user', 'detail'])->findOrFail($id);

            // Kiểm tra trạng thái hiện tại
            if ($withdraw->RequestStatus != 'N') {
                return redirect()->back()
                    ->with('error', 'Yêu cầu này đã được xử lý. Không thể hủy.');
            }

            // Cập nhật trạng thái thành đã hủy (C = Cancel)
            // Khi RequestStatus = 'C', middleware UpdateUserWallet sẽ tự động trả tiền lại
            $withdraw->update([
                'RequestStatus' => 'C',
                'Status' => 'C',
                'Note' => ($withdraw->Note ?? '') . ' [ĐÃ HỦY: ' . ($request->input('reject_reason') ?? 'Không có lý do') . ']',
            ]);

            // Cập nhật detail nếu có
            if ($withdraw->detail) {
                $withdraw->detail->update([
                    'Status' => 'C',
                    'DateEnd' => now(),
                    'Description' => ($withdraw->detail->Description ?? '') . ' [ĐÃ HỦY: ' . ($request->input('reject_reason') ?? 'Không có lý do') . ']',
                ]);
            }

            // Gửi thông báo Telegram
            $admin = auth()->guard('admin')->user();
            $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

            $tgMessage = "❌ <b>HỦY RÚT TIỀN</b>\n\n";
            $tgMessage .= "Admin: <b>{$adminName}</b>\n";
            $tgMessage .= "Mã GD: <code>{$withdraw->SerialCode}</code>\n";
            $tgMessage .= "User: {$withdraw->user->UserName} (ID: {$withdraw->user->UserID})\n";
            $tgMessage .= "Số tiền: " . number_format($withdraw->detail->PV, 0, ',', '.') . " VND\n";
            $tgMessage .= "Lý do: " . ($request->input('reject_reason') ?? 'Không có lý do') . "\n";
            $tgMessage .= "Thời gian: " . now()->format('d/m/Y H:i:s');
            $this->sendMesssageTelegram($tgMessage, 'withdraw');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Hủy rút tiền thành công! Tiền đã được trả lại cho user. Mã GD: ' . $withdraw->SerialCode);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject withdraw error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi hủy rút tiền: ' . $e->getMessage());
        }
    }
}
