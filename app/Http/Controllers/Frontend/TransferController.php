<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TransGdRequirement;
use App\Models\TransPdRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Facade\BaseHelperFacade;
use App\Traits\Telegram;

class TransferController extends Controller
{
    use Telegram;

    public function index()
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->route(BaseHelperFacade::getUserPrefix() . '.login');
        }

        // Lấy số dư các ví từ user
        $walletBalances = [
            'thankhoan' => $user->THANKHOAN ?? 0,
            'thanhvien' => $user->THANHVIEN ?? 0,
            'tieudung' => $user->TIEUDUNG ?? 0,
        ];

        // Lịch sử chuyển (TransGdRequirement) - UserID là người gửi
        $sentTransfers = TransGdRequirement::where('UserID', $user->UserID)
            ->where('VIP', 'TRANSFER')
            ->where('RequestStatus', 'Y')
            ->with('fUser') // Load relationship với người nhận (FUserID)
            ->orderBy('RequestDate', 'desc')
            ->limit(50)
            ->get();

        // Lịch sử nhận (TransPdRequirement) - UserID là người nhận
        $receivedTransfers = TransPdRequirement::where('UserID', $user->UserID)
            ->where('VIP', 'TRANSFER')
            ->where('RequestStatus', 'Y')
            ->with('fUser') // Load relationship với người gửi (FUserID)
            ->orderBy('RequestDate', 'desc')
            ->limit(50)
            ->get();

        return view('page.frontend.transfer', [
            'userPrefix' => BaseHelperFacade::getUserPrefix(),
            'walletBalances' => $walletBalances,
            'sentTransfers' => $sentTransfers,
            'receivedTransfers' => $receivedTransfers,
        ]);
    }

    /**
     * Xử lý chuyển khoản nội bộ
     */
    public function doTransfer(Request $request)
    {
        $request->validate([
            'wallet_type' => 'required|in:THANKHOAN,THANHVIEN,TIEUDUNG',
            'amount' => 'required|numeric|min:1000',
            'receiver' => 'required|string',
        ], [
            'wallet_type.required' => 'Vui lòng chọn ví chuyển',
            'wallet_type.in' => 'Loại ví không hợp lệ',
            'amount.required' => 'Vui lòng nhập số tiền',
            'amount.numeric' => 'Số tiền phải là số',
            'amount.min' => 'Số tiền tối thiểu là 1,000',
            'receiver.required' => 'Vui lòng nhập thông tin người nhận',
        ]);

        try {
            DB::beginTransaction();

            $sender = Auth::guard('web')->user();
            if (!$sender) {
                return redirect()->back()->with('error', 'Vui lòng đăng nhập để thực hiện chuyển khoản');
            }

            // Tìm người nhận theo username, email hoặc UserID
            $receiver = User::where('UserName', $request->input('receiver'))
                ->orWhere('Email', $request->input('receiver'))
                ->orWhere('UserID', $request->input('receiver'))
                ->first();

            if (!$receiver) {
                return redirect()->back()->with('error', 'Không tìm thấy người nhận. Vui lòng kiểm tra lại thông tin');
            }

            if ($receiver->UserID == $sender->UserID) {
                return redirect()->back()->with('error', 'Không thể chuyển tiền cho chính mình');
            }

            // Kiểm tra RankID trong bảng tbl_node
            $receiverNode = \App\Models\TblNode::where('UserID', $receiver->UserID)->first();
            if (!$receiverNode || $receiverNode->RankID != 1) {
                return redirect()->back()->with('error', 'Người nhận phải là đại lý mới được nhận tiền');
            }

            $walletType = $request->input('wallet_type');
            $amount = (float) $request->input('amount');

            // Kiểm tra số dư ví
            $currentBalance = $sender->$walletType ?? 0;

            if ($currentBalance < $amount) {
                return redirect()->back()->with('error', 'Số dư ví không đủ. Số dư hiện tại: ' . number_format($currentBalance, 0, ',', '.') . ' đ');
            }

            // Tạo mã giao dịch
            $serialCode = 'TRF' . strtoupper(Str::random(5));

            TransGdRequirement::create([
                'OrderID' => 0,
                'UserID' => $sender->UserID,
                'FUserID' => $receiver->UserID,
                'TransactionID' => 0,
                'Currency' => $walletType,
                'VIP' => 'TRANSFER',
                'AmountTransfer' => $amount,
                'Fee' => 0,
                'SerialCode' => $serialCode,
                'RequestDate' => now(),
                'RequestStatus' => 'Y',
                'Note' => "Chuyển khoản nội bộ đến {$receiver->UserName} số tiền: " . $amount . " ví: " . $walletType,
                'IP' => $request->ip(),
                'isPT' => 0,
                'Status' => 'Y',
            ]);

            TransPdRequirement::create([
                'UserID' => $receiver->UserID,
                'FUserID' => $sender->UserID,
                'TransactionID' => 0,
                'Currency' => $walletType,
                'Image' => '',
                'VIP' => 'TRANSFER',
                'AmountTransfer' => $amount,
                'Amount' => $amount,
                'SerialCode' => $serialCode,
                'RequestDate' => now(),
                'DateMaintain' => now(),
                'RequestStatus' => 'Y',
                'Status' => 'Y',
                'Note' => "Chuyển khoản nội bộ từ {$sender->UserName} số tiền: " . $amount . " ví: " . $walletType,
                'IP' => $request->ip(),
                'isMove' => 0,
                'STSCNID' => 0,
            ]);


            $tgMessage = "✅ Chuyển khoản nội bộ thành công!\n";
            $tgMessage .= "Mã GD: $serialCode\n";
            $tgMessage .= "Người gửi: {$sender->UserName} (ID: {$sender->UserID})\n";
            $tgMessage .= "Người nhận: {$receiver->UserName} (ID: {$receiver->UserID})\n";
            $tgMessage .= "Số tiền: " . number_format($amount, 0, ',', '.') . " đ\n";
            $tgMessage .= "Ví: $walletType\n";
            $tgMessage .= "Thời gian: " . now()->format('d/m/Y H:i:s');
            $this->sendMesssageTelegram($tgMessage, 'transfer');

            DB::commit();

            return redirect()->back()->with('success', 'Chuyển khoản thành công! Mã giao dịch: ' . $serialCode);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transfer error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi chuyển khoản: ' . $e->getMessage());
        }
    }

    /**
     * Search user by username or email
     */
    public function searchUser(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập thông tin tìm kiếm'
            ]);
        }

        // Tìm user theo UserName hoặc Email
        $user = User::where(function ($q) use ($query) {
            $q->where('UserName', $query)
                ->orWhere('Email', $query);
        })->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'userName' => $user->UserName,
                    'fullName' => $user->FullName ?? '',
                    'email' => $user->Email ?? '',
                    'userID' => $user->UserID,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy người dùng với thông tin này'
        ]);
    }
}
