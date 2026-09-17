<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram;

use App\Traits\ElasticMail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ChangeEmail;
use App\Http\Requests\Frontend\User\ChangeEmailRequest as ChangeEmailRequestValidation;


class DashboardController extends BaseController
{
    use ImageUpload,Telegram,ElasticMail;

    public function index()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để xem thông tin cá nhân.');
        }

        return view('pages.frontend.dashboard', [
            'user' => $user,
            'userPrefix' => $this->userPrefix
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'CMND' => 'required|string|max:15',
            'birthday' => 'required|date',
            'address' => 'required|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // Max 1MB
        ], [
            'fullName.required' => 'Vui lòng nhập họ tên',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'CMND.required' => 'Vui lòng nhập CCCD/CMND',
            'birthday.required' => 'Vui lòng nhập ngày sinh',
            'address.required' => 'Vui lòng nhập địa chỉ',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::guard('web')->user();
            if (!$user) {
                return redirect()->back()->with('error', 'Vui lòng đăng nhập để cập nhật thông tin');
            }

            $updateData = [
                'FullName' => $request->input('fullName'),
                'Phone' => $request->input('phone') ?? '',
                'CMND' => $request->input('CMND') ?? '',
                'Birthday' => $request->input('birthday') ?? '',
                'Address' => $request->input('address') ?? '',
            ];
            // Upload avatar nếu có
            if ($request->hasFile('avatar')) {
                $imageLink = $this->getLinkImage($request->file('avatar'));
                if (!empty($imageLink)) {
                    $updateData['Avatar'] = $imageLink;
                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Không thể upload ảnh đại diện. Vui lòng thử lại.');
                }
            }
            $user->update($updateData);

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Update profile error: ' . $exception->getMessage(), [
                'user_id' => Auth::guard('web')->id(),
                'trace' => $exception->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
        }
    }

    /**
     * Cập nhật thông tin tài khoản ngân hàng
     */
    public function updateBankInfo(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ], [
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng',
            'account_number.required' => 'Vui lòng nhập số tài khoản',
            'account_name.required' => 'Vui lòng nhập tên chủ tài khoản',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::guard('web')->user();
            if (!$user) {
                return redirect()->back()->with('error', 'Vui lòng đăng nhập để cập nhật thông tin');
            }

            $updateData = [
                'NganHang' => $request->input('bank_name'),
                'STK' => $request->input('account_number'),
                'Ten_TK' => $request->input('account_name'),
            ];

            $user->update($updateData);

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật thông tin ngân hàng thành công!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Update bank info error: ' . $exception->getMessage(), [
                'user_id' => Auth::guard('web')->id(),
                'trace' => $exception->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
        }
    }


    /**
     * Yêu cầu đổi email - Gửi email xác nhận đến email cũ
     */
    public function requestChangeEmail(ChangeEmailRequestValidation $request)
    {
        try {
            $user = Auth::guard('web')->user();
            $newEmail = $request->input('new_email');

            DB::beginTransaction();

            // Hủy các yêu cầu đang pending của user này
            ChangeEmail::where('UserID', $user->UserID)
                ->where('Status', 'pending')
                ->update(['Status' => 'cancelled']);

            // Tạo token xác nhận
            $verifyToken = Str::random(60);
            // Tạo yêu cầu đổi email mới
            $changeRequest = ChangeEmail::create([
                'UserID' => $user->UserID,
                'OldEmail' => $user->Email,
                'NewEmail' => $newEmail,
                'VerifyToken' => $verifyToken,
                'Status' => 'pending',
                'IPAddress' => $request->ip(),
                'UserAgent' => $request->userAgent(),
                'CreatedAt' => now(),
                'ExpiredAt' => now()->addHours(24), // Hết hạn sau 24 giờ
            ]);

            // Gửi email xác nhận đến email CŨ
            $emailData = [
                'UserName' => $user->UserName,
                'FullName' => $user->FullName ?? '',
                'OldEmail' => $user->Email,
                'NewEmail' => $newEmail,
                'VerifyUrl' => route($this->userPrefix . '.confirmChangeEmail', ['token' => $verifyToken]),
                'ExpiredAt' => $changeRequest->ExpiredAt->format('d/m/Y H:i'),
            ];

            $this->sendElasticEmail(
                'antruongtho.com - Xác nhận thay đổi địa chỉ email',
                $user->Email,
                'emails.change-email-confirm',
                $emailData,
                'change-email'
            );

            DB::commit();

            return redirect()->back()->with('success', 
                'Yêu cầu đổi email đã được gửi. Vui lòng kiểm tra Email ' . $user->Email . ' để xác nhận.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error requesting change email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã có lỗi vui lòng thử lại sau.! ');
        }
    }

    /**
     * Xác nhận đổi email qua link trong email
     */
    public function confirmChangeEmail($token)
    {
        try {
            $changeRequest = ChangeEmail::where('VerifyToken', $token)->first();
            if (!$changeRequest) {
                return redirect()->route($this->userPrefix . '.login')
                    ->with('error', 'Yêu cầu không tồn tại hoặc đã bị hủy.');
            }

            // Kiểm tra trạng thái
            if ($changeRequest->Status !== 'pending') {
                $message = match($changeRequest->Status) {
                    'confirmed' => 'Email đã được thay đổi trước đó.',
                    'expired' => 'Yêu cầu đã hết hạn.',
                    'cancelled' => 'Yêu cầu đã bị hủy.',
                    default => 'Yêu cầu không hợp lệ.',
                };
                if (Auth::guard('web')->guest()) {
                    return redirect()->route($this->userPrefix . '.login')->with('error', $message);
                }else {
                    return redirect()->route($this->userPrefix . '.dashboard')->with('error', $message);
                }
            }

            // Kiểm tra hết hạn
            if ($changeRequest->isExpired()) {
                $changeRequest->update(['Status' => 'expired']);
                return redirect()->route($this->userPrefix . '.dashboard')
                    ->with('error', 'Yêu cầu đã hết hạn. Vui lòng tạo yêu cầu mới.');
            }

            // Kiểm tra email mới có bị trùng không
            // $emailExists = User::where('Email', $changeRequest->NewEmail)
            //     ->where('UserID', '!=', $changeRequest->UserID)
            //     ->exists();

            // if ($emailExists) {
            //     $changeRequest->update(['Status' => 'cancelled']);
            //     return redirect()->route($this->userPrefix . '.dashboard')
            //         ->with('error', 'Email mới đã được sử dụng bởi tài khoản khác.');
            // }

            DB::beginTransaction();

            // Cập nhật email cho user
            $user = User::findOrFail($changeRequest->UserID);
            $user->update([
                'Email' => $changeRequest->NewEmail,
            ]);

            // Cập nhật trạng thái yêu cầu
            $changeRequest->update([
                'Status' => 'confirmed',
                'ConfirmedAt' => now(),
            ]);

            $telegramMessage = "✅ <b>USER ĐỔI EMAIL THÀNH CÔNG</b>\n\n"
                . "UserID: <code>{$user->UserID}</code>\n"
                . "UserName: <b>{$user->UserName}</b>\n"
                . "Full Name: {$user->FullName}\n"
                . "Email cũ: {$changeRequest->OldEmail}\n"
                . "Email mới: {$changeRequest->NewEmail}\n"
                . "IP: " . request()->ip() . "\n"
                . "Time: " . now()->format('Y-m-d H:i:s');

            $this->sendMesssageTelegram($telegramMessage);

            DB::commit();

            // Đăng xuất user để yêu cầu đăng nhập lại
            // Auth::guard('web')->logout();

            if (Auth::guard('web')->guest()) {
                return redirect()->route($this->userPrefix . '.login')
                ->with('success', 'Email đã được thay đổi thành công. Vui lòng đăng nhập lại.');
            }else {
                return redirect()->route($this->userPrefix . '.dashboard')
                ->with('success', 'Email đã được thay đổi thành công.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error confirming change email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã có lỗi vui lòng thử lại sau.! ');
        }
    }
}
