<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TransGdRequirement;
use App\Models\TransGdRequirementsDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Facade\BaseHelperFacade;
use App\Traits\Telegram;

class WalletController extends BaseController
{
    use Telegram;

    private const WITHDRAW_FEE_RATE = 0.10;
    private const EXCHANGE_RATE = 10000;
    private const WITHDRAW_LIMIT = 50; // 50PA

    public function index()
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->route($this->userPrefix . '.login');
        }

        // Lịch sử rút tiền với join trans_gd_requirements_detail để lấy dữ liệu đầy đủ
        $withdrawHistory = TransGdRequirement::select([
            'trans_gd_requirements.*',
            'trans_gd_requirements_detail.AmountFee as TaxAmount',
            'trans_gd_requirements_detail.PV as FinalAmount',
            'trans_gd_requirements_detail.FullName',
            'trans_gd_requirements_detail.BTCAddress as BankAccount',
            'trans_gd_requirements_detail.BankName',
            'trans_gd_requirements_detail.Description',
            'trans_gd_requirements_detail.Currency as DetailCurrency',
        ])
            ->leftJoin('trans_gd_requirements_detail', 'trans_gd_requirements.RequestID', '=', 'trans_gd_requirements_detail.IDGD')
            ->where('trans_gd_requirements.UserID', $user->UserID)
            ->where('trans_gd_requirements.VIP', 'WITHDRAW')
            ->orderBy('trans_gd_requirements.RequestDate', 'desc')
            ->limit(50)
            ->get();

        return view('page.frontend.wallet.index', [
            'withdrawHistory' => $withdrawHistory,
            'user' => $user,
            'userPrefix' => $this->userPrefix,
            'fee' => self::WITHDRAW_FEE_RATE,
            'exchangeRate' => self::EXCHANGE_RATE,
            'withdrawLimit' => self::WITHDRAW_LIMIT,
        ]);
    }

    /**
     * Xử lý rút tiền
     */
    public function doWithdraw(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để thực hiện rút tiền');
        }

        if (empty($user->NganHang) || empty($user->STK) || empty($user->Ten_TK)) {
            return redirect()->back()
                ->with('error', 'Vui lòng cập nhật thông tin tài khoản ngân hàng trước khi rút tiền.')
                ->withInput();
        }

        $request->validate([
            'wallet_type' => 'required|in:CRWallet',
            'amount' => 'required|numeric|min:' . self::WITHDRAW_LIMIT . '|max:' . ($user->CRWallet ?? 0),
        ], [
            'wallet_type.required' => 'Vui lòng chọn ví rút',
            'wallet_type.in' => 'Loại ví không hợp lệ',
            'amount.required' => 'Vui lòng nhập số tiền',
            'amount.numeric' => 'Số tiền phải là số',
            'amount.min' => 'Số tiền tối thiểu là ' . self::WITHDRAW_LIMIT . ' PA',
            'amount.max' => 'Số tiền rút không được vượt quá số dư hiện tại',
        ]);

        try {
            DB::beginTransaction();

            $walletType = $request->input('wallet_type');
            $paAmount = (float) $request->input('amount');

            $currentBalance = $user->$walletType ?? 0;

            if ($currentBalance < $paAmount) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Số dư ví không đủ. Số dư hiện tại: ' . number_format($currentBalance, 2, ',', '.') . ' PA');
            }

            $bankName = $user->NganHang;
            $bankAccount = $user->STK;
            $accountName = $user->Ten_TK;

            // Tính toán phí và số tiền thực nhận
            $exchangeRate = self::EXCHANGE_RATE;
            $fee = self::WITHDRAW_FEE_RATE;

            // Quy đổi PA sang VND
            $vndAmount = $paAmount * $exchangeRate;

            // Tính thuế (10% của VND)
            $taxAmount = $vndAmount * $fee;

            // Số tiền thực nhận (VND - Thuế)
            $finalVndAmount = $vndAmount - $taxAmount;

            $serialCode = 'WDR' . strtoupper(Str::random(5));
            $transGdRequirement = TransGdRequirement::create([
                'OrderID' => 0,
                'UserID' => $user->UserID,
                'FUserID' => $user->UserID,
                'TransactionID' => 0,
                'Currency' => "PA",
                'VIP' => 'WITHDRAW',
                'AmountTransfer' => $paAmount,
                'Fee' => $fee * 100,
                'SerialCode' => $serialCode,
                'RequestDate' => now(),
                'RequestStatus' => 'N',
                'Note' => "Rút tiền ví PA: {$paAmount}, Quy đổi VND: " . number_format($vndAmount, 0, ',', '.') . ", Thuế (10%): " . number_format($taxAmount, 0, ',', '.') . ", Thực nhận: " . number_format($finalVndAmount, 0, ',', '.') . ". Số tài khoản: {$bankAccount}, Ngân hàng: {$bankName}, Chủ TK: {$accountName}",
                'IP' => $request->ip(),
                'isPT' => 0,
                'Rate' => $exchangeRate,
                'Status' => 'N',
            ]);

            TransGdRequirementsDetail::create([
                'IDGD' => $transGdRequirement->RequestID,
                'FullName' => $accountName,
                'BTCAddress' => $bankAccount,
                'BankName' => $bankName,
                'BTCImage' => '',
                'Hash' => '',
                'Status' => 'N',
                'Email' => null,
                'DateStart' => now(),
                'DateEnd' => null,
                'Amount' => $paAmount,
                'PV' => $finalVndAmount,
                'Fee' => $fee * 100,
                'AmountFee' => $taxAmount,
                'Description' => "Rút tiền - Mã GD: {$serialCode}. Số PA: {$paAmount}, Thực nhận: " . number_format($finalVndAmount, 0, ',', '.') . " VND",
                'Currency' => "PA",
            ]);

            // Gửi thông báo Telegram
            $tgMessage = "YÊU CẦU RÚT TIỀN !\n";
            $tgMessage .= "Mã GD: $serialCode\n";
            $tgMessage .= "User: {$user->UserName} (ID: {$user->UserID})\n";
            $tgMessage .= "Số PA rút: " . $paAmount . " PA\n";
            $tgMessage .= "Quy đổi VND: " . number_format($vndAmount, 0, ',', '.') . " VND\n";
            $tgMessage .= "Thuế TNCN (" . (self::WITHDRAW_FEE_RATE * 100) . "%): " . number_format($taxAmount, 0, ',', '.') . " VND\n";
            $tgMessage .= "Thực nhận: " . number_format($finalVndAmount, 0, ',', '.') . " VND\n";
            $tgMessage .= "Số dư PA trước khi rút: " . number_format($currentBalance, 2, ',', '.') . " PA\n";
            $tgMessage .= "Số dư PA sau khi rút: " . number_format($currentBalance - $paAmount, 2, ',', '.') . " PA\n";
            $tgMessage .= "Số TK: {$bankAccount}\n";
            $tgMessage .= "Ngân hàng: {$bankName}\n";
            $tgMessage .= "Chủ TK: {$accountName}\n";
            $tgMessage .= "Thời gian: " . now()->format('d/m/Y H:i:s');
            $this->sendMesssageTelegram($tgMessage, 'withdraw');

            DB::commit();

            return redirect()->back()->with('success', 'Yêu cầu rút tiền đã được gửi! Mã giao dịch: ' . $serialCode . '. Thực nhận: ' . number_format($finalVndAmount, 0, ',', '.') . ' VND. Vui lòng chờ admin duyệt.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdraw error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi rút tiền: ' . $e->getMessage());
        }
    }
}
