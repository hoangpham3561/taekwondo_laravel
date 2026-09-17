<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Forgot2FARequest;
use App\Http\Requests\Frontend\User\ChangeAvatarRequest;
use App\Http\Requests\Frontend\User\ChangePasswordRequest;
use App\Http\Requests\Frontend\User\Enable2FARequest;
use App\Http\Requests\Frontend\User\KycAccountRequest;
use App\Services\Frontend\UserService;
use App\Http\Controllers\Api\user\UserController as ApiUserController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Models\UserKycLog;
use App\Models\VoSinh;
use App\Models\KhoaHoc;
use App\Models\News;
use Auth;
use Illuminate\Http\Request;
use App\Models\TblNodeDownlineLogRef;
use App\Models\TblNode;
use App\Traits\Telegram;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\UpgradeF1Indirect;

class UserController extends BaseController
{
    use Telegram, UpgradeF1Indirect;
    protected $userService;
    protected $apiUserController;
    protected $apiAuthController;

    public function __construct(
        UserService $userService,
        ApiUserController $apiUserController,
        ApiAuthController $apiAuthController
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->apiUserController = $apiUserController;
        $this->apiAuthController = $apiAuthController;
    }

    public function index()
    {
        $voSinh = Auth::guard('web')->user();
        if (!$voSinh) {
            $voSinh = VoSinh::with('capDai')
                ->where('active_status', true)
                ->orderByDesc('id')
                ->first();
        } else {
            $voSinh->loadMissing('capDai');
        }

        $lichHoc = KhoaHoc::query()
            ->where('is_active', true)
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        $thongBao = News::query()
            ->where(function ($query) {
                $query->where('is_published', true)
                    ->orWhere('status', 'active');
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('pages.frontend.user', [
            'userPrefix' => $this->userPrefix,
            'voSinh' => $voSinh,
            'lichHoc' => $lichHoc,
            'thongBao' => $thongBao,
        ]);
    }

    public function kyc($token)
    {
        // Lấy vo sinh từ token trước khi gọi kyc()
        $userKycLog = UserKycLog::where('verify_token', $token)->first();
        $user = null;

        if ($userKycLog) {
            $user = VoSinh::find($userKycLog->user_id);
        }

        // Gọi method kyc() để xử lý
        $result = $this->userService->kyc($token);

        if ($result && $user) {
            try {
                // Note: TblNode may not be needed for vo_sinh
                // Commenting out node creation logic as vo_sinh doesn't have UserID/F1UserID
                // $existingNode = TblNode::where('UserID', $user->id)->first();
                // if (!$existingNode) {
                //     $node = new TblNode();
                //     $node->setAttribute('NodeID', $user->id);
                //     $node->UserID = $user->id;
                //     $node->DateCreate = now();
                //     $node->save();
                // }

                // Note: upgrade_f1_indirect may not be applicable for vo_sinh
                // $this->upgrade_f1_indirect($user->id, $user->id, 1, now());

                // Note: vo_sinh doesn't have F1UserID field
                // $f1User = VoSinh::where('id', $user->f1_user_id)->where('active_status', true)->first();

                // Gửi thông báo Telegram
                $telegramMessage = "✅ <b>KYC Email Thành Công</b>\n\n"
                    . "ID: <code>{$user->id}</code>\n"
                    . "Mã hội viên: <b>{$user->ma_hoi_vien}</b>\n"
                    . "Email: {$user->email}\n"
                    . "Họ tên: {$user->ho_va_ten}\n"
                    . "IP: " . request()->ip() . "\n"
                    . "Time: " . now()->format('Y-m-d H:i:s');

                try {
                    $this->sendMesssageTelegram($telegramMessage);
                } catch (\Exception $e) {
                    Log::error('Telegram error khi gửi thông báo KYC: ' . $e->getMessage());
                }

                Auth::guard('web')->logout();
                return redirect()->route($this->userPrefix . '.login')->with('success', 'KYC thành công. Vui lòng đăng nhập');
            } catch (\Exception $e) {
                Log::error('Lỗi khi xử lý sau KYC: ' . $e->getMessage());
                Auth::guard('web')->logout();
                return redirect()->route($this->userPrefix . '.login')->with('success', 'KYC thành công. Vui lòng đăng nhập');
            }
        }

        return redirect()->route($this->userPrefix . '.login')->with('error', 'KYC không thành công');
    }

    public function showChangePassword()
    {
        $userResponse = $this->apiAuthController->me(request());
        $userData = json_decode($userResponse->getContent(), true);
        
        if (!$userData['success'] || $userResponse->getStatusCode() === 401) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để đổi mật khẩu.');
        }

        $user = (object) ($userData['data'] ?? []);
        
        return view('page.frontend.change-password', [
            'user' => $user,
            'userPrefix' => $this->userPrefix,
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $apiResponse = $this->apiUserController->changePassword($request);
        $result = json_decode($apiResponse->getContent(), true);
        
        $statusCode = $apiResponse->getStatusCode();
        
        if ($result['success'] && $statusCode === 200) {
            return redirect()->route($this->userPrefix . '.changePassword')
                ->with('success', $result['message'] ?? 'Đổi mật khẩu thành công!');
        }
        
        return redirect()->route($this->userPrefix . '.changePassword')
            ->with('error', $result['message'] ?? 'Đổi mật khẩu thất bại. Vui lòng thử lại.');
    }

    public function changeAvatar(ChangeAvatarRequest $request)
    {
        $this->userService->changeAvatar($request);
        return redirect()->route('.settings')->with('success', 'Change avatar successfully');
    }

    public function enable2FA(Enable2FARequest $request)
    {
        $result = $this->userService->enable2FA($request);
        if ($result) {
            return redirect()->route('.settings')->with('success', 'Change 2fa successfully');
        } else {
            return redirect()->route('.settings')->with('error', 'Cannot change switch on/off 2fa');
        }
    }

    public function forgot2FA(Forgot2FARequest $request)
    {
        $result = $this->userService->forgot2FA($request);
        if ($result) {
            return redirect()->route('.settings')->with('success', 'Please check your email to activate 2FA');
        } else {
            return redirect()->route('.settings')->with('error', 'Cannot forgot 2FA');
        }
    }

    public function kycAccount(KycAccountRequest $request)
    {
        $result = $this->userService->kycAccount($request);
        if ($result) {
            return redirect()->route('.settings')->with('success', 'Submit kyc successfully');
        } else {
            return redirect()->route('.settings')->with('error', 'Cannot submit KYC');
        }
    }
}
