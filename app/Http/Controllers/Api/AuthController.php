<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\VoSinh;
use App\Services\Frontend\UserService;
use App\Traits\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use Telegram;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Đăng ký user mới
     */
    public function register(RegisterRequest $request)
    {
        try {
            $refIdInput = $request->input('ref_id');
            $referrerUserID = null;
            $referrerUserName = null;

            // Nếu có ref_id từ request, tìm vo sinh theo ma_hoi_vien
            if (!empty($refIdInput)) {
                $refUser = VoSinh::where('ma_hoi_vien', $refIdInput)
                    ->where('active_status', true)
                    ->first();

                if ($refUser) {
                    $referrerUserID = $refUser->id;
                    $referrerUserName = $refUser->ho_va_ten;
                }
            }
            // Nếu không tìm thấy hoặc không có ref_id, dùng vo sinh đầu tiên (id = 1)
            if (!$referrerUserID) {
                $rootUser = VoSinh::where('id', 1)
                    ->where('active_status', true)
                    ->first();
                if ($rootUser) {
                    $referrerUserID = $rootUser->id;
                    $referrerUserName = $rootUser->ho_va_ten;
                }
            }

            // Merge ReferrerUserID vào request để UserService xử lý
            if ($referrerUserID) {
                $request->merge([
                    'ReferrerUserID' => $referrerUserID,
                    'F1UserID' => $referrerUserID
                ]);
            }

            $result = $this->userService->signUp($request);

            if ($result) {
                // Lấy vo sinh vừa tạo bằng ma_hoi_vien hoặc email
                $user = VoSinh::where('ma_hoi_vien', $request->username)
                    ->orWhere('email', $request->email)
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy tài khoản vừa tạo.',
                    ], 400);
                }

                // Note: vo_sinh table doesn't have api_token field
                // Token generation may need to be handled differently
                $token = Str::random(60);

                // Gửi thông báo Telegram khi đăng ký thành công
                try {
                    $telegramMessage = "✅ <b>ĐĂNG KÝ TÀI KHOẢN MỚI</b>\n\n"
                        . "ID: <code>{$user->id}</code>\n"
                        . "Mã hội viên: <b>{$user->ma_hoi_vien}</b>\n"
                        . "Email: {$user->email}\n"
                        . "Họ tên: {$user->ho_va_ten}\n"
                        . "IP: " . $request->ip() . "\n"
                        . "Time: " . now()->format('Y-m-d H:i:s');

                    $this->sendMesssageTelegram($telegramMessage);
                } catch (\Exception $e) {
                    // Log lỗi nhưng không làm fail request
                    Log::error('Telegram error khi gửi thông báo đăng ký: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Đăng ký thành công',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Đăng ký thất bại',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Đăng nhập
     */
    public function login(LoginRequest $request)
    {
        $loginField = trim($request->input('email')); // Có thể là username hoặc email
        $password = $request->input('password');

        // Validate input không được rỗng
        if (empty($loginField) || empty($password)) {
            throw ValidationException::withMessages([
                'email' => ['Vui lòng nhập đầy đủ thông tin đăng nhập.'],
            ]);
        }

        // Tìm vo sinh theo ma_hoi_vien hoặc email
        // Ưu tiên tìm theo ma_hoi_vien trước, nếu không tìm thấy mới tìm theo email
        $user = VoSinh::where('ma_hoi_vien', $loginField)
            ->where('active_status', true)
            ->first();

        // Nếu không tìm thấy theo ma_hoi_vien, thử tìm theo email
        if (!$user) {
            $user = VoSinh::where('email', $loginField)
                ->where('active_status', true)
                ->first();
        }

        // Kiểm tra user có tồn tại không
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Tài khoản không tồn tại hoặc chưa được kích hoạt.'],
            ]);
        }

        // Kiểm tra password - so sánh trực tiếp vì password lưu dạng plain text
        // Trim password để tránh lỗi do whitespace
        $storedPassword = trim($user->password);
        $inputPassword = trim($password);

        if ($storedPassword !== $inputPassword) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        // Generate token và lưu vào database
        $token = Str::random(60);
        
        // Lưu token vào database
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        // Xóa token trong bảng vo_sinh
        $user = $request->user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ]);
    }

    /**
     * Lấy thông tin user hiện tại
     * Hỗ trợ cả session auth (frontend) và API token auth
     */
    public function me(Request $request)
    {
        // Lấy user từ session (cho frontend) hoặc từ API token
        $user = Auth::guard('web')->user() ?? $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Quên mật khẩu - gửi mật khẩu mới qua email
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Gọi method từ UserService
        $result = $this->userService->forgotPassword(
            $email,
            $request->ip(),
            $request->userAgent()
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }
    }
}
