<?php

namespace App\Services\Frontend;

use App\Enums\KycStatusEnum;
use App\Enums\KycTypeEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserKycLog;
use App\Services\BaseService;
use App\Traits\ElasticMail;
use App\Traits\Upload;
use App\Traits\UpgradeF1Indirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\TblNodeDownlineLogRef;
use App\Models\TblNode;
use App\Models\TblLogChangePassUser;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{
    use ElasticMail, Upload, UpgradeF1Indirect;

    public function signUp($request)
    {
        $input = $request->all();

        // Nếu không có ref_id, dùng mã đỉnh (UserID = 1)
        if (empty($input['ReferrerUserID'])) {
            $rootUser = User::where('UserID', 1)->where('Active', 'Y')->first();
            if ($rootUser) {
                $input['ReferrerUserID'] = $rootUser->UserID;
                $input['F1UserID'] = $rootUser->UserID;
            }
        }

        // Map các field từ form sang cấu trúc bảng mới
        $userData = [
            'UserName' => $input['username'],
            'Email' => $input['email'],
            'Pass' => $input['password'],
            'PassMD5' => md5($input['password']),
            'Active' => 'N',
            'VerifyKYC' => 'N',
            'VerifyKYC2' => 'N',
            'FullName' => $input['full_name'],
            'ReferrerUserID' => $input['ReferrerUserID'] ?? 0,
            'F1UserID' => $input['F1UserID'] ?? 0,
            'Pos' => 'A',
            'Type' => 0,
            'DateReg' => now(),
            'CreatedAt' => now(),
            'Sys' => 'V',
            'Birthday' => '',
            'CMND' => '',
            'CMND_NgayCap' => '',
            'CMND_NoiCap' => '',
            'Gender' => '',
            'STK' => '',
            'Ten_TK' => '',
            'KHUVUC' => '',
            'NganHang' => '',
            'MST' => '',
            'MaFree' => '',
            'Country' => 'US',
            'Address' => '',
            'Name_ship' => '',
            'Address_ship' => '',
            'ETHAddress' => '',
            'BTCAddress' => '',
            'USDTAddress' => '',
            'Telegram' => '',
            'City' => '',
            'State' => '',
            'District' => '',
            'PostCode' => '',
            'Ward' => '',
            'Tel' => '',
            'PhraseID' => 0,
            'StatusMember' => '',
            '2FA' => 'N',
            'lockTransfer' => 'N',
            'IP' => request()->ip(),
            'TokenID' => Str::random(10),
            'Avatar' => 'avatar.png',
            'ActiveMarket' => 'N',
        ];

        try {
            DB::beginTransaction();
            $user = User::create($userData);

            if (!empty($user)) {
                $verifyToken = Str::random(30);
                $userKycLog = UserKycLog::create([
                    'user_id' => $user->UserID, 
                    'verify_token' => $verifyToken,
                    'type' => KycTypeEnum::EMAIL_SIGN_UP,
                    'status' => KycStatusEnum::SENT
                ]);

                if (!empty($userKycLog)) {
                    // Send KYC Email
                    $data = $user;
                    $data['verify_token'] = $verifyToken;

                    $this->sendElasticEmail('antruongtho.com - KYC Email', $user->Email, 'emails.send-kyc', $data, 'kyc');
                    DB::commit();
                    return true;
                }
                ////////nếu gửi mail để kích hoặt tài khoản thì mở lại 


                // $existingNode = TblNode::where('UserID', $user->UserID)->first();
                // if (!$existingNode) {
                //     TblNode::create([
                //         'UserID' => $user->UserID,
                //         'DateCreate' => now(),
                //     ]);
                // }
                // $this->upgrade_f1_indirect($user->UserID, $user->UserID, 1, now());
                ////////nếu KHÔMG GỬI MAIL THÌ DÙNG CODE NÀY

                DB::commit();
                return $user;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Sign up error: ' . $exception->getMessage());
            \Log::error('Sign up error trace: ' . $exception->getTraceAsString()); // Thêm dòng này để debug tốt hơn
        }
        return false;
    }

    public function kyc($token)
    {
        $userKycLog = UserKycLog::query()->where('verify_token', $token)
            ->where('status', KycStatusEnum::SENT)->first();

        if (!empty($userKycLog)) {
            // Validate expired time
            $isExpired = false;
            switch ($userKycLog->type) {
                case KycTypeEnum::LOST_2FA:
                case KycTypeEnum::LOST_PASS:
                case KycTypeEnum::WALLET:
                    $expiredAt = Carbon::parse($userKycLog->expired_at);
                    $now = Carbon::now();
                    $isExpired = $expiredAt->diffInHours($now) > 1;
                    break;
            }
            if (!$isExpired) {
                try {
                    DB::beginTransaction();
                    // Find user - dùng UserID thay vì id
                    $user = User::query()->where('UserID', $userKycLog->user_id)->first();
                    if (!empty($user)) {
                        // Update kyc log
                        $userKycLog->update([
                            'status' => KycStatusEnum::ACTIVATED
                        ]);

                        // Building update data base on KYC type
                        $updateData = [];
                        switch ($userKycLog->type) {
                            case KycTypeEnum::LOST_2FA:
                                $google2fa = app('pragmarx.google2fa');
                                $updateData = [
                                    '2FACode' => $google2fa->generateSecretKey() // Dùng 2FACode thay vì secret_2fa
                                ];
                                break;
                            case KycTypeEnum::LOST_PASS:
                            case KycTypeEnum::WALLET:
                                break;
                            case KycTypeEnum::EMAIL_SIGN_UP:
                                $updateData = [
                                    'Email' => $user->Email, // Giữ nguyên email
                                    'Active' => 'Y', // Dùng Active = 'Y' thay vì status
                                    'VerifyKYC' => 'Y'
                                ];
                                break;
                        }
                        $user->update($updateData);
                        DB::commit();
                        return true;
                    }
                } catch (\Exception $exception) {
                    DB::rollback();
                    \Log::error('KYC error: ' . $exception->getMessage());
                }
            }
        }
        return false;
    }

    public function changePassword($request)
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            
            $user = Auth::guard('web')->user();
            
            if (empty($user)) {
                DB::rollBack();
                throw new \Exception('Người dùng không tồn tại.');
            }
            
            // Kiểm tra mật khẩu cũ
            if ($input['old_password'] !== $user->Pass) {
                DB::rollBack();
                throw new \Exception('Mật khẩu cũ không đúng.');
            }
            
            // Kiểm tra mật khẩu mới và xác nhận
            if ($input['new_password'] !== $input['new_password_confirmation']) {
                DB::rollBack();
                throw new \Exception('Mật khẩu xác nhận không khớp.');
            }
            
            // Kiểm tra mật khẩu mới phải khác mật khẩu cũ
            if (Hash::check($input['new_password'], $user->Pass)) {
                DB::rollBack();
                throw new \Exception('Mật khẩu mới phải khác mật khẩu cũ.');
            }
            
            // Cập nhật mật khẩu mới
            $user->update([
                'Pass' => $input['new_password'],
                'PassMD5' => md5($input['new_password']),
            ]);
            
            DB::commit();
            return true;
            
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Change password error: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function changeAvatar($request)
    {
        $input = $request->all();
        if (!empty($request['images'])) {
            if (!empty(Auth::guard('web')->user()->userInfo->avatar)) {
                $this->deleteImage('upload', Auth::guard('web')->user()->userInfo->avatar);
            }
            $input['images'] = $this->doUpload('jpg|png|gif', 'upload', 'images');
        }
        try {
            DB::beginTransaction();
            UserInfo::updateOrCreate(
                ['user_id' => Auth::guard('web')->user()->id],
                ['avatar' => $input['images']]
            );
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }

    public function enable2FA($request)
    {
        $input = $request->all();
        $google2fa = (new Google2FA());
        $valid = $google2fa->verifyKey(Auth::guard('web')->user()->secret_2fa, $input['secret_2fa'], 0);
        if ($valid) {
            Auth::guard('web')->user()->is_lock_2fa = !Auth::guard('web')->user()->is_lock_2fa;
            Auth::guard('web')->user()->save();
            return true;
        }
        return false;
    }

    public function forgot2FA($request)
    {
        $input = $request->all();
        if ($input['email'] === Auth::guard('web')->user()->email) {
            try {
                DB::beginTransaction();
                // disable 2fa
                Auth::guard('web')->user()->is_lock_2fa = 1;
                Auth::guard('web')->user()->save();

                // Generate new token and send mail
                $verifyToken = Str::random(30);
                $userKycLog = UserKycLog::create([
                    'user_id' => Auth::guard('web')->user()->id,
                    'verify_token' => $verifyToken,
                    'type' => KycTypeEnum::LOST_2FA,
                    'status' => KycStatusEnum::SENT
                ]);
                if (!empty($userKycLog)) {
                    // Send KYC Email
                    $data = Auth::guard('web')->user();
                    $data['verify_token'] = $verifyToken;
                    $this->sendElasticEmail('Forgot 2FA Email', Auth::guard('web')->user()->email, 'emails.forgot-2fa', $data);
                    DB::commit();
                    return true;
                }
            } catch (\Exception $exception) {
                DB::rollBack();
            }
        }
        return false;
    }

    public function kycAccount($request)
    {
        $input = $request->all();
        if (!empty($request['identity_card_img']) && !empty($request['identity_card_back_img']) && !empty($request['identity_card_selfie_img'])) {
            // Upload
            if (!empty(Auth::guard('web')->user()->userInfo->identity_card_img)) {
                $this->deleteImage('upload', Auth::guard('web')->user()->userInfo->identity_card_img);
            }
            $input['identity_card_img'] = $this->doUpload('jpg|png|gif', 'upload', 'identity_card_img');

            if (!empty(Auth::guard('web')->user()->userInfo->identity_card_back_img)) {
                $this->deleteImage('upload', Auth::guard('web')->user()->userInfo->identity_card_back_img);
            }
            $input['identity_card_back_img'] = $this->doUpload('jpg|png|gif', 'upload', 'identity_card_back_img');

            if (!empty(Auth::guard('web')->user()->userInfo->identity_card_selfie_img)) {
                $this->deleteImage('upload', Auth::guard('web')->user()->userInfo->identity_card_selfie_img);
            }
            $input['identity_card_selfie_img'] = $this->doUpload('jpg|png|gif', 'upload', 'identity_card_selfie_img');
        }
        try {
            DB::beginTransaction();
            Auth::guard('web')->user()->name = $input['name'];
            Auth::guard('web')->user()->save();
            UserInfo::updateOrCreate(
                ['user_id' => Auth::guard('web')->user()->id],
                [
                    'identity_card_img' => $input['identity_card_img'],
                    'identity_card_back_img' => $input['identity_card_back_img'],
                    'identity_card_selfie_img' => $input['identity_card_selfie_img'],
                    'identity_card' => $input['identity_card']
                ]
            );
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }

    public function forgotPassword($email, $ipAddress = null, $userAgent = null)
    {
        try {
            $user = User::where('Email', $email)
                ->where('Active', 'Y')
                ->first();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Email không tồn tại trong hệ thống hoặc tài khoản chưa được kích hoạt.'
                ];
            }

            $newPassword = Str::random(10);
            $oldPassword = $user->Pass;

            DB::beginTransaction();

            TblLogChangePassUser::create([
                'UserID' => $user->UserID,
                'Email' => $email,
                'OldPassword' => $oldPassword,
                'NewPassword' => $newPassword,
                'IPAddress' => $ipAddress ?? request()->ip(),
                'UserAgent' => $userAgent ?? request()->userAgent(),
                'CreatedAt' => now(),
            ]);

            $user->update([
                'Pass' => $newPassword,
                'PassMD5' => md5($newPassword),
            ]);

            $emailData = [
                'UserName' => $user->UserName,
                'FullName' => $user->FullName ?? '',
                'Email' => $user->Email,
                'newPassword' => $newPassword,
            ];

            $this->sendElasticEmail(
                'antruongtho.com - Forgot Password',
                $user->Email,
                'emails.forgot-password',
                $emailData,
                'forgot-password'
            );

            DB::commit();

            return [
                'success' => true,
                'message' => 'Mật khẩu mới đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Forgot password error: ' . $e->getMessage(), [
                'email' => $email,
                'ip' => $ipAddress ?? request()->ip(),
                'exception' => $e
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi khôi phục mật khẩu. Vui lòng thử lại sau.'
            ];
        }
    }
}
