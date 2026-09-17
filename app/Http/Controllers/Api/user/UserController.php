<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Services\Frontend\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        // Validate request
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password',
            'new_password_confirmation' => 'required|min:6|same:new_password',
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.different' => 'Mật khẩu mới phải khác mật khẩu cũ.',
            'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'new_password_confirmation.min' => 'Mật khẩu xác nhận phải có ít nhất 6 ký tự.',
            'new_password_confirmation.same' => 'Mật khẩu xác nhận không khớp với mật khẩu mới.',
        ]);

        // Lấy user từ session (cho frontend) hoặc từ API token
        $user = Auth::guard('web')->user() ?? $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập.',
            ], 401);
        }

        try {
            // Tạo một FormRequest giả để tương thích với UserService
            $changePasswordRequest = new \App\Http\Requests\Frontend\User\ChangePasswordRequest();
            $changePasswordRequest->merge($request->all());
            
            $result = $this->userService->changePassword($changePasswordRequest);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đổi mật khẩu thành công!',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Đổi mật khẩu thất bại. Vui lòng thử lại.',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Change password error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}