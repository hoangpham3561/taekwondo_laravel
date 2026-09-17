<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BankTransferDeposit;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DepositController extends BaseController
{
    use ImageUpload;

    public function index()
    {
        $products = DB::table('goi_hoc_phi')
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->selectRaw('id as ProductID, name as Name, price as Amount')
            ->get();

        $userPrefix = $this->userPrefix;

        $user = Auth::guard('web')->user();
        $orders = collect([]);

        $orderCode = 'NAP' . now()->format('ymdHis') . strtoupper(Str::random(4));

        // Lấy số dư các ví từ user
        $walletBalances = [
            'thankhoan' => 0,
            'thanhvien' => 0,
            'tieudung' => 0,
            'odicaffee' => 0,
            'khuyenmai' => 0,
            'usdt' => 0,
        ];

        if ($user) {
            $orders = BankTransferDeposit::query()
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
            $walletBalances = [
                'thankhoan' => $user->THANKHOAN ?? 0,
                'thanhvien' => $user->THANHVIEN ?? 0,
                'tieudung' => $user->TIEUDUNG ?? 0,
                'odicaffee' => $user->ODICAFFEE ?? 0,
                'khuyenmai' => $user->KHUYENMAI ?? 0,
                'usdt' => $user->USDT_WALLET ?? 0,
            ];
        }

        $bankTransferConfig = [
            'bank_code' => env('BANK_TRANSFER_BANK_CODE', 'ACB'),
            'bank_name' => env('BANK_TRANSFER_BANK_NAME', 'ACB'),
            'account_number' => env('BANK_TRANSFER_ACCOUNT_NUMBER', '12244821'),
            'account_name' => env('BANK_TRANSFER_ACCOUNT_NAME', 'NGUYEN VAN PHUONG'),
        ];

        return view('pages.frontend.deposit', compact(
            'products',
            'userPrefix',
            'orders',
            'walletBalances',
            'orderCode',
            'bankTransferConfig'
        ))->with('pendingDepositId', session('pending_deposit_id'));
    }

    public function doDeposit(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:goi_hoc_phi,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::guard('web')->user();
            if (!$user) {
                return redirect()->back()->with('error', 'Vui lòng đăng nhập để thực hiện nạp tiền');
            }

            $product = DB::table('goi_hoc_phi')->where('id', $request->input('product_id'))->first();
            if (!$product) {
                return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
            }

            $proofImage = null;
            if ($request->hasFile('image')) {
                $proofImage = $this->getLinkImage($request->file('image'));
                if (empty($proofImage)) {
                    return redirect()->back()->with('error', 'Không thể upload ảnh chứng từ. Vui lòng thử lại');
                }
            }

            $transferNote = strtoupper(trim((string) $request->input('note')));
            if ($transferNote === '') {
                $transferNote = 'NAP' . $user->id . now()->format('ymdHis') . strtoupper(Str::random(4));
            }

            if (BankTransferDeposit::query()->where('transfer_note', $transferNote)->exists()) {
                $transferNote = 'NAP' . $user->id . now()->format('ymdHis') . strtoupper(Str::random(6));
            }

            $amount = (float) $product->price;
            $bankCode = env('BANK_TRANSFER_BANK_CODE', 'ACB');
            $accountNumber = env('BANK_TRANSFER_ACCOUNT_NUMBER', '12244821');
            $accountName = env('BANK_TRANSFER_ACCOUNT_NAME', 'NGUYEN VAN PHUONG');

            $qrUrl = sprintf(
                'https://img.vietqr.io/image/%s-%s-compact2.png?amount=%d&addInfo=%s&accountName=%s',
                urlencode($bankCode),
                urlencode($accountNumber),
                (int) $amount,
                urlencode($transferNote),
                urlencode($accountName)
            );

            $deposit = BankTransferDeposit::query()->create([
                'user_id' => $user->id,
                'package_id' => $product->id,
                'amount' => $amount,
                'transfer_note' => $transferNote,
                'status' => 'N',
                'proof_image' => $proofImage,
                'bank_code' => $bankCode,
                'bank_name' => env('BANK_TRANSFER_BANK_NAME', 'ACB'),
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'qr_url' => $qrUrl,
                'meta' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Đã tạo lệnh nạp. Vui lòng chuyển khoản đúng nội dung để hệ thống tự xác nhận.')
                ->with('pending_deposit_id', $deposit->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deposit error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::guard('web')->id(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại sau');
        }
    }

    public function checkStatus(BankTransferDeposit $deposit): JsonResponse
    {
        $user = Auth::guard('web')->user();
        if (!$user || $deposit->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $deposit->id,
            'status' => $deposit->status,
            'status_text' => $deposit->status_text,
            'paid_at' => optional($deposit->paid_at)->format('d/m/Y H:i:s'),
        ]);
    }

    public function webhook(Request $request): JsonResponse
    {
        $secret = env('BANK_WEBHOOK_SECRET');
        if (!empty($secret) && $request->header('X-Bank-Webhook-Secret') !== $secret) {
            return response()->json(['message' => 'Invalid webhook secret'], 401);
        }

        $transferNote = strtoupper(trim((string) ($request->input('transfer_note') ?? $request->input('description') ?? $request->input('content'))));
        $amount = (float) ($request->input('amount') ?? 0);
        $txnRef = (string) ($request->input('transaction_id') ?? $request->input('txn_ref') ?? '');

        if ($transferNote === '' || $amount <= 0) {
            return response()->json(['message' => 'Invalid payload'], 422);
        }

        $deposit = BankTransferDeposit::query()
            ->where('transfer_note', $transferNote)
            ->where('amount', $amount)
            ->where('status', 'N')
            ->first();

        if (!$deposit) {
            return response()->json(['message' => 'No matching pending deposit'], 404);
        }

        $deposit->update([
            'status' => 'Y',
            'paid_at' => now(),
            'bank_txn_ref' => $txnRef ?: $deposit->bank_txn_ref,
            'meta' => array_merge((array) $deposit->meta, [
                'webhook_payload' => $request->all(),
                'webhook_at' => now()->toDateTimeString(),
            ]),
        ]);

        return response()->json(['message' => 'Deposit marked as paid']);
    }
}
