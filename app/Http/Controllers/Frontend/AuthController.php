<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\Auth\ForgotPasswordRequest;
use App\Models\VoSinh;
use App\Models\UserKycLog;
use App\Enums\KycTypeEnum;
use App\Enums\KycStatusEnum;
use App\Services\Frontend\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\Telegram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    use Telegram;
    protected $userService;
    protected $apiAuthController;

    public function __construct(
        UserService $userService,
        \App\Http\Controllers\Api\AuthController $apiAuthController
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->apiAuthController = $apiAuthController;
    }

    public function login()
    {
        return view('pages.frontend.login');
    }

    public function logout()
    {
        // Note: vo_sinh table doesn't have Session_login field
        Auth::guard('web')->logout();
        return redirect()->route($this->userPrefix . '.login');
    }

    /**
     * Tạo session từ API token (sau khi API login thành công)
     */
    public function createSessionFromToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $token = $request->input('token');
        $remember = $request->input('remember', false);

        // Tìm vo sinh theo api_token
        $user = VoSinh::where('api_token', $token)
            ->where('active_status', true)
            ->first();

        if (!$user) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Token không hợp lệ hoặc đã hết hạn.');
        }

        // Đăng nhập user
        Auth::guard('web')->login($user, $remember);

        return redirect()->route($this->userPrefix . '.index');
    }

    /**
     * @param string|null $refCode
     */
    public function signUp($refCode = null)
    {
        $refId = null;
        $refUserName = null;

        // Nếu có ref_code từ URL, tìm theo ma_hoi_vien
        if ($refCode) {
            $refUser = VoSinh::where('ma_hoi_vien', $refCode)
                ->where('active_status', true)
                ->first();

            if ($refUser) {
                $refId = $refUser->ma_hoi_vien;
                $refUserName = $refUser->ho_va_ten;
            }
        }

        return view('pages.frontend.sign-up', compact('refId', 'refUserName'));
    }

    public function forgotPassword()
    {
        return view('pages.frontend.forgot-password');
    }

    public function doForgotPassword(ForgotPasswordRequest $request)
    {
        // Gọi API để xử lý quên mật khẩu
        $apiResponse = $this->apiAuthController->forgotPassword($request);
        $result = json_decode($apiResponse->getContent(), true);

        if ($result['success']) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }
    }

    public function previewEmail(Request $request)
    {
        // Tạo dữ liệu mẫu để preview
        $data = [
            'UserName' => $request->input('username', 'Nguyễn Văn A'),
            'username' => $request->input('username', 'nguyenvana'),
            'Email' => $request->input('email', 'test@example.com'),
            'verify_token' => $request->input('token', 'test-token-123456789012345678901234567890'),
            'userPrefix' => $this->userPrefix,
        ];

        // Render email template
        return view('emails.send-kyc', ['data' => $data]);
    }
}
