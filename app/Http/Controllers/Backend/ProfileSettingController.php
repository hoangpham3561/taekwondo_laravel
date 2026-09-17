<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\ProfileSetting\UpdateProfileRequest;
use App\Http\Requests\Backend\ProfileSetting\ChangePasswordRequest;
use App\Traits\ImageUpload;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileSettingController extends BaseController
{
    use ImageUpload;

    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Refresh user to get latest data from database
        if ($admin) {
            $admin->refresh();
        }

        return view('layout.backend.profile-setting.index', [
            'admin' => $admin,
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            $data = [
                'ho_va_ten' => $request->ho_va_ten,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            // Handle photo upload if provided
            if ($request->hasFile('photo_url')) {
                try {
                    $photoUrl = $this->getLinkImage($request->file('photo_url'));
                    if (!empty($photoUrl)) {
                        $data['photo_url'] = $photoUrl;
                    } else {
                        Log::error('Upload image failed: getLinkImage returned empty', [
                            'admin_id' => $admin->id,
                            'file_name' => $request->file('photo_url')->getClientOriginalName(),
                            'file_size' => $request->file('photo_url')->getSize(),
                        ]);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Không thể upload ảnh đại diện. Vui lòng thử lại hoặc chọn ảnh khác.');
                    }
                } catch (\Exception $uploadException) {
                    Log::error('Upload image exception: ' . $uploadException->getMessage(), [
                        'admin_id' => $admin->id,
                        'trace' => $uploadException->getTraceAsString(),
                    ]);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Lỗi khi upload ảnh: ' . $uploadException->getMessage());
                }
            }

            $admin->update($data);

            // Reload user from database to get fresh data
            $admin = User::find($admin->id);
            
            // Update user in auth session to reflect changes immediately
            Auth::guard('admin')->setUser($admin);

            return redirect()->route($this->adminPrefix . '.profile-setting.index')
                ->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            // Check old password (plain text comparison as per AuthController)
            if ($admin->password !== $request->old_password) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Mật khẩu cũ không đúng.');
            }

            // Update password (plain text storage as per system design)
            $admin->update([
                'password' => $request->new_password,
            ]);

            return redirect()->route($this->adminPrefix . '.profile-setting.index')
                ->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
