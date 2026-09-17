<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\TransPdRequirement;
use App\Models\TransGdRequirement;
use App\Models\TblTransaction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram;

class UpdateUserWallet
{
    use Telegram;
    /**
     * Handle an incoming request.
     * Tự động cập nhật số dư ví cho user đã đăng nhập
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            try {
                // Cập nhật số dư ví ngay lập tức (không dùng cache)
                $this->updateCWallet($user->UserID);

                // Refresh lại user model từ database để đảm bảo dữ liệu mới nhất
                // Điều này đảm bảo controller sẽ nhận được số dư đã cập nhật
                $user = User::find($user->UserID)->refresh();

                // Cập nhật lại user trong session/auth để các request tiếp theo cũng có dữ liệu mới
                Auth::guard('web')->setUser($user);
            } catch (\Exception $e) {
                // Log lỗi nhưng không chặn request
                Log::error('Update wallet middleware error', [
                    'user_id' => $user->UserID,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $next($request);
    }

    /**
     * Cập nhật lại các ví tiền của user
     * 
     * @param int $userID User ID cần cập nhật
     * @return void
     */
    private function updateCWallet($userID)
    {
        $usdtpd = TransPdRequirement::where('UserID', $userID)
            ->where('Currency', 'USDT')
            ->where('RequestStatus', 'Y')
            ->sum('AmountTransfer') ?? 0;

        $usdtgd = TransGdRequirement::where('UserID', $userID)
            ->where('Currency', 'USDT')
            ->where('RequestStatus', '!=', 'C')
            ->sum('AmountTransfer') ?? 0;

        $usdt = $usdtpd - $usdtgd;

        //ví VND 
        $VNDpd = TransPdRequirement::where('UserID', $userID)
            ->where('Currency', 'VND')
            ->where('RequestStatus', 'Y')
            ->sum('AmountTransfer') ?? 0;

        $PD_VND_Transaction = TblTransaction::where('user_id', $userID)
            ->where('status', 'Y')
            ->where('currency', 'VND')
            ->sum('point') ?? 0;

        $VNDgd = TransGdRequirement::where('UserID', $userID)
            ->where('Currency', 'VND')
            ->where('RequestStatus', '!=', 'C')
            ->sum('AmountTransfer') ?? 0;

        $VND = ($VNDpd + $PD_VND_Transaction) - $VNDgd;



        //Ví PA
        $PAPd = TransPdRequirement::where('UserID', $userID)
            ->where('Currency', 'PA')
            ->where('RequestStatus', 'Y')
            ->sum('AmountTransfer') ?? 0;

        $PD_PA_Transaction = TblTransaction::where('user_id', $userID)
            ->where('status', 'Y')
            ->where('currency', 'PA')
            ->sum('point') ?? 0;

        $PAGd = TransGdRequirement::where('UserID', $userID)
            ->where('Currency', 'PA')
            ->where('RequestStatus', '!=', 'C')
            ->sum('AmountTransfer') ?? 0;

        $PA = ($PAPd + $PD_PA_Transaction) - $PAGd;

        if ($usdt >= 0 && $VND >= 0 && $PA >= 0) {
            User::where('UserID', $userID)->update([
                'USDT_WALLET' => $usdt,
                'UserAmount' => $VND,
                'CRWallet' => $PA,
            ]);
        } else {
            // Nếu có giá trị âm, set về 0 và vẫn cập nhật
            $usdt = $usdt < 0 ? 0 : $usdt;
            $VND = $VND < 0 ? 0 : $VND;
            $PA = $PA < 0 ? 0 : $PA;

            User::where('UserID', $userID)->update([
                'USDT_WALLET' => $usdt,
                'UserAmount' => $VND,
                'CRWallet' => $PA,
            ]);

            // Gửi thông báo Telegram nếu có giá trị âm lớn hơn -1
            if ($usdt < -1 || $VND < -1 || $PA < -1) {

                $user = User::where('UserID', $userID)->first();
                $telegramMessage = "Minus UserID: {$userID} - user name: {$user->UserName} \n"
                    . "USDT: {$usdt}\n"
                    . "VND: {$VND}\n"
                    . "PA: {$PA}";
                $this->sendMesssageTelegram($telegramMessage);
            }
        }
    }
}
