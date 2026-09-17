<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Auth\LoginRequest;
use App\Services\CaptchaService;
use Illuminate\Support\Facades\Auth;
use App\Traits\Telegram;
use App\Traits\LogActivitiesAdm;
use Illuminate\Support\Carbon;
use App\Helpers\BaseHelper;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    use Telegram, LogActivitiesAdm;

    protected $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    public function login()
    {
        $this->captchaService->generate();

        return view('pages.login');
    }

    /**
     * Handle admin login with CAPTCHA verification
     */
    public function doLogin(LoginRequest $request)
    {
        $username = trim($request->get('username'));
        $password = $request->get('password');
        $ip = $request->ip();
        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        // Find user from Database only (table huan_luyen_vien), not from seeder
        // Match by ma_hoi_vien, email, ho_va_ten (tên), or phone (sdt); only active admin/owner
        $user = User::where('is_active', true)
            ->whereIn('role', ['owner', 'admin'])
            ->where(function ($q) use ($username) {
                $usernameLower = mb_strtolower($username);
                $q->whereRaw('LOWER(TRIM(ma_hoi_vien)) = ?', [$usernameLower])
                    ->orWhereRaw('LOWER(TRIM(email)) = ?', [$usernameLower])
                    ->orWhereRaw('LOWER(TRIM(ho_va_ten)) = ?', [$usernameLower])
                    ->orWhereRaw('TRIM(phone) = ?', [trim($username)]);
            })
            ->first();

        if (!$user) {
            // User not found or doesn't meet conditions (is_active, role)
            $this->captchaService->generate();
            $this->adminLoginTelegram($username, '', $dateTime, $ip, 'Login Fail: user not found or inactive/unauthorized role');
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đăng nhập thất bại. Tài khoản không tồn tại hoặc không có quyền truy cập.');
        }
        // Verify password (plain text comparison)
        if ($user->password === $password) {
            // Login successful - manually log in the user
            Auth::guard('admin')->login($user);

            $this->adminLoginTelegram($username, 'CAPTCHA Verified', $dateTime, $ip, 'Login Success');

            $baseHelper = new BaseHelper();
            $adminPrefix = $baseHelper->getAdminPrefix();

            // Get logged in admin info
            $admin = Auth::guard('admin')->user();
            $roleName = $admin->getRoleName();

            switch ($roleName) {
                case 'admin_ketoan':
                    return redirect()->route($adminPrefix . '.orders.index')
                        ->with('success', 'Đăng nhập thành công!');

                case 'super_admin':
                    return redirect()->route($adminPrefix . '.dashboard')
                        ->with('success', 'Đăng nhập thành công!');

                default:
                    return redirect()->route($adminPrefix . '.dashboard')
                        ->with('success', 'Đăng nhập thành công!');
            }
        }

        // Generate new CAPTCHA on failed login
        $this->captchaService->generate();

        // Push notification admin login fail on telegram channel
        $this->adminLoginTelegram($username, '', $dateTime, $ip, 'Login Fail: wrong password');
        return redirect()->back()
            ->withInput()
            ->with('error', 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $baseHelper = new BaseHelper();
        $adminPrefix = $baseHelper->getAdminPrefix();

        return redirect()->route($adminPrefix . '.login');
    }
}
