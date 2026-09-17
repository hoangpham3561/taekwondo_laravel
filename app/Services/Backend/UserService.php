<?php

namespace App\Services\Backend;

use App\Enums\KycStatusEnum;
use App\Enums\KycTypeEnum;
use App\Enums\NewsStatusEnum;
use App\Enums\UserStatusEnum;
use App\Models\News;
use App\Models\User;
use App\Models\UserKycLog;
use App\Services\BaseService;
use App\Traits\ElasticMail;
use App\Traits\Telegram;
use App\Traits\Upload;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{
    use ElasticMail, Telegram;

    public function getAll(Request $request)
    {
        $filters = $request->all();

        // perPage handle
        if (!empty($filters['perPage']) && $filters['perPage'] > 15) {
            $this->perPage = $filters['perPage'];
        }

        $query = User::query()->with(['userInfo', 'node']);

        $query = $query->where('Active', 'Y');

        // Tìm kiếm theo UserName, Email hoặc FullName
        if (!empty($filters['username'])) {
            $searchTerm = $filters['username'];
            $query = $query->where(function ($q) use ($searchTerm) {
                $q->where('UserName', 'like', '%' . $searchTerm . '%')
                    ->orWhere('Email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('FullName', 'like', '%' . $searchTerm . '%');
            });
        }

        if (isset($filters['muc_id']) && $filters['muc_id'] !== '' && $filters['muc_id'] !== 'all') {
            $query->whereHas('node', function ($q) use ($filters) {
                $q->where('MucID', $filters['muc_id']);
            });
        }

        if (isset($filters['level_id']) && $filters['level_id'] !== '' && $filters['level_id'] !== 'all') {
            // Trường hợp đặc biệt: Đại lý chuyên nghiệp (LevelID = 0 && MucID >= 1)
            if ($filters['level_id'] == '0') {
                $query->whereHas('node', function ($q) {
                    $q->where('LevelID', 0)
                    ->where('MucID', '<', 1);
                });
            }
            else {
                $query->whereHas('node', function ($q) use ($filters) {
                    $q->where('LevelID', $filters['level_id']);
                });
            }
        }

        return $query->orderBy('UserID', 'DESC')->paginate($this->perPage);
    }

    /**
     * Lấy danh sách user chưa active (Active = 'N')
     */
    public function getInactiveUsers(Request $request)
    {
        $filters = $request->all();

        // perPage handle
        if (!empty($filters['perPage']) && $filters['perPage'] > 15) {
            $this->perPage = $filters['perPage'];
        }

        $query = User::query()->with(['userInfo', 'node']);

        // Lấy user có Active = 'N'
        $query = $query->where('Active', 'N');

        // Tìm kiếm theo UserName, Email hoặc FullName
        if (!empty($filters['username'])) {
            $searchTerm = $filters['username'];
            $query = $query->where(function ($q) use ($searchTerm) {
                $q->where('UserName', 'like', '%' . $searchTerm . '%')
                    ->orWhere('Email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('FullName', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lọc theo Đại lý (RankID = 1)
        if (isset($filters['is_agent']) && $filters['is_agent'] !== '') {
            if ($filters['is_agent'] == '1') {
                // Chỉ lấy user là đại lý (RankID = 1)
                $query = $query->whereHas('node', function ($q) {
                    $q->where('RankID', 1);
                });
            } else {
                // Lấy user không phải đại lý (RankID != 1 hoặc không có node)
                $query = $query->where(function ($q) {
                    $q->whereDoesntHave('node')
                        ->orWhereHas('node', function ($subQ) {
                            $subQ->where('RankID', '!=', 1);
                        });
                });
            }
        }

        if (!empty($filters['status'])) {
            $query = $query->when($filters['status'] === UserStatusEnum::ACTIVE, function ($q) use ($filters) {
                return $q->where('status', $filters['status']);
            })
                ->when($filters['status'] === UserStatusEnum::INACTIVE, function ($q) use ($filters) {
                    return $q->where('status', $filters['status']);
                });
        }

        return $query->orderBy('UserID', 'DESC')->paginate($this->perPage);
    }

    public function store($request)
    {
        $data = $request->all();
        // Modify some data
        $randomPassword = Str::random(16);
        $data['password_no_encrypt'] = $randomPassword;
        $data['password'] = $randomPassword;
        $google2fa = app('pragmarx.google2fa');
        $data['secret_2fa'] = $google2fa->generateSecretKey();
        if (!empty($data['ref_id'])) {
            $data['ref_id'] = User::where('username', $data['ref_id'])->first()->id;
        }

        return User::create($data);
    }

    public function update($request, $id)
    {
        $data = $request->all();
        $model = new User();
        $user = $model->with(['userInfo', 'refId'])->findOrFail($id);

        if (!empty($data['ref_id'])) {
            // Sửa: Tìm user theo UserName và lấy UserID
            $refUser = User::where('UserName', $data['ref_id'])->first();
            if ($refUser) {
                $data['ReferrerUserID'] = $refUser->UserID; // Sửa: Dùng ReferrerUserID thay vì ref_id
            }
        }

        // Map các field từ form sang database
        $updateData = [
            'UserName' => $data['username'] ?? $user->UserName,
            'Email' => $data['email'] ?? $user->Email,
            'FullName' => $data['name'] ?? $user->FullName,
            'Active' => $data['status'] ?? $user->Active,
        ];

        if ($request->has('Pass')) {
            $updateData['Pass'] = $data['Pass'];
            $updateData['PassMD5'] = md5($data['Pass']);
        }

        // Thêm ReferrerUserID nếu có
        if (isset($data['ReferrerUserID'])) {
            $updateData['ReferrerUserID'] = $data['ReferrerUserID'];
        }

        // If status is deleted, use soft delete
        // Otherwise, clear deleted_by & deleted_at
        if ($request['status'] !== UserStatusEnum::DELETED) {
            $updateData['deleted_at'] = null;
            $updateData['deleted_by'] = null;
            $result = $user->update($updateData);

            // Xử lý set đơn hàng (MucID) từ select
            if ($request->has('muc_id')) {
                $mucID = (int)$request->input('muc_id');
                $this->updateMucID($id, $mucID);
            }

            // Xử lý set đồng chia (RankID) từ select - giữ nguyên nếu vẫn cần
            if ($request->has('rank_id')) {
                $rankID = (int)$request->input('rank_id');
                $this->updateRankID($id, $rankID);
            }

            // Xử lý set danh hiệu (LevelID) từ select
            if ($request->has('level_title_id')) {
                $levelTitleID = (int)$request->input('level_title_id');
                $this->updateLevelTitleID($id, $levelTitleID);
            }

            // Xử lý set Level (level_id number) - giữ nguyên logic cũ nếu cần
            if ($request->has('level_id')) {
                $this->updateLevelID($id, $request->input('level_id'));
            }

            return $result;
        } else {
            return $user->delete();
        }
    }

    /**
     * Set user lên đại lý (RankID = 1)
     *
     * @param int $userId
     * @return void
     */
    private function setAgentRank($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $node = \App\Models\TblNode::where('UserID', $userId)->first();
        if ($node) {
            $node->update(['RankID' => 1]);

            $admin = Auth::guard('admin')->user();
            $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

            $telegramMessage = "✅ <b>SET ĐẠI LÝ THÀNH CÔNG</b>\n\n"
                . "Admin: <b>{$adminName}</b>\n"
                . "UserID: <code>{$user->UserID}</code>\n"
                . "UserName: <b>{$user->UserName}</b>\n"
                . "FullName: {$user->FullName}\n"
                . "Email: {$user->Email}\n"
                . "Time: " . now()->format('Y-m-d H:i:s');

            try {
                $this->sendMesssageTelegram($telegramMessage);
            } catch (\Exception $e) {
                Log::error('Telegram error khi set đại lý: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove đại lý (RankID = 0)
     *
     * @param int $userId
     * @return void
     */
    private function removeAgentRank($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $node = \App\Models\TblNode::where('UserID', $userId)->first();

        if ($node && $node->RankID == 1) {
            $node->update(['RankID' => 0]);

            $admin = Auth::guard('admin')->user();
            $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

            $telegramMessage = "❌ <b>HỦY ĐẠI LÝ</b>\n\n"
                . "Admin: <b>{$adminName}</b>\n"
                . "UserID: <code>{$user->UserID}</code>\n"
                . "UserName: <b>{$user->UserName}</b>\n"
                . "FullName: {$user->FullName}\n"
                . "Email: {$user->Email}\n"
                . "Time: " . now()->format('Y-m-d H:i:s');

            try {
                $this->sendMesssageTelegram($telegramMessage);
            } catch (\Exception $e) {
                Log::error('Telegram error khi hủy đại lý: ' . $e->getMessage());
            }
        }
    }

    /**
     * Cập nhật LevelID trong tbl_node
     *
     * @param int $userId
     * @param int $levelID Level từ 0-100 (0 để reset)
     * @return void
     */
    private function updateLevelID($userId, $levelID)
    {
        $node = \App\Models\TblNode::where('UserID', $userId)->first();

        if ($node) {
            // Validate levelID trong khoảng 0-100
            $levelID = max(0, min(100, (int)$levelID));

            // Lưu giá trị cũ trước khi update
            $oldLevelID = $node->LevelID;

            // Chỉ update và gửi thông báo nếu có thay đổi
            if ($oldLevelID != $levelID) {
                $node->update(['LevelID' => $levelID]);

                $user = User::find($userId);
                $userName = $user ? $user->UserName : 'Unknown';

                $admin = Auth::guard('admin')->user();
                $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

                $telegramMessage = "✅ <b>Cập nhật LevelID thành công</b>\n\n"
                    . "Admin: <b>{$adminName}</b>\n"
                    . "UserID: <code>{$userId}</code>\n"
                    . "UserName: <b>{$userName}</b>\n"
                    . "LevelID: <code>{$oldLevelID} ----> {$levelID}</code>\n"
                    . "Time: " . now()->format('Y-m-d H:i:s');

                try {
                    $this->sendMesssageTelegram($telegramMessage);
                } catch (\Exception $e) {
                    Log::error('Telegram error khi cập nhật levelID: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Cập nhật RankID trong tbl_node (Đồng chia/Không đồng chia)
     *
     * @param int $userId
     * @param int $rankID 0 = Không đồng chia, 1 = Đồng chia
     * @return void
     */
    private function updateRankID($userId, $rankID)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }
        $node = \App\Models\TblNode::where('UserID', $userId)->first();
        if ($node) {
            // Lưu giá trị cũ trước khi update
            $oldRankID = $node->RankID;

            // Validate rankID chỉ nhận 0 hoặc 1
            $rankID = in_array($rankID, [0, 1]) ? $rankID : 0;

            // Chỉ update và gửi thông báo nếu có thay đổi
            if ($oldRankID != $rankID) {
                $node->update(['RankID' => $rankID]);

                $admin = Auth::guard('admin')->user();
                $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

                $statusText = $rankID == 1 ? 'Có' : 'Không';
                $oldStatusText = $oldRankID == 1 ? 'Có' : 'Không';

                $telegramMessage = "✅ <b>SET CỬA HÀNG </b>\n\n"
                    . "Admin: <b>{$adminName}</b>\n"
                    . "UserID: <code>{$user->UserID}</code>\n"
                    . "UserName: <b>{$user->UserName}</b>\n"
                    . "FullName: {$user->FullName}\n"
                    . "Email: {$user->Email}\n"
                    . "RankID: <code>{$oldRankID} ({$oldStatusText}) ----> {$rankID} ({$statusText})</code>\n"
                    . "Time: " . now()->format('Y-m-d H:i:s');

                try {
                    $this->sendMesssageTelegram($telegramMessage);
                } catch (\Exception $e) {
                    Log::error('Telegram error khi cập nhật RankID: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Cập nhật LevelID (danh hiệu) trong tbl_node
     *
     * @param int $userId
     * @param int $levelTitleID 0-4: Danh hiệu
     * @return void
     */
    private function updateLevelTitleID($userId, $levelTitleID)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $node = \App\Models\TblNode::where('UserID', $userId)->first();

        if ($node) {
            // Lưu giá trị cũ trước khi update
            $oldLevelID = $node->LevelID;

            // Validate levelTitleID chỉ nhận 0-4
            $levelTitleID = in_array($levelTitleID, [0, 1, 2, 3, 4]) ? $levelTitleID : 0;

            // Chỉ update và gửi thông báo nếu có thay đổi
            if ($oldLevelID != $levelTitleID) {
                $node->update(['LevelID' => $levelTitleID]);

                $admin = Auth::guard('admin')->user();
                $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

                $levelNames = [
                    0 => 'Không có',
                    1 => 'Giám Sát Kinh Doanh',
                    2 => 'Quản Lý Kinh Doanh',
                    3 => 'Phó Giám Đốc Kinh Doanh',
                ];

                $levelName = $levelNames[$levelTitleID] ?? 'N/A';
                $oldLevelName = $levelNames[$oldLevelID] ?? 'N/A';

                $telegramMessage = "✅ <b>CẬP NHẬT DANH HIỆU</b>\n\n"
                    . "Admin: <b>{$adminName}</b>\n"
                    . "UserID: <code>{$user->UserID}</code>\n"
                    . "UserName: <b>{$user->UserName}</b>\n"
                    . "FullName: {$user->FullName}\n"
                    . "Email: {$user->Email}\n"
                    . "LevelID: <code>{$oldLevelID} ({$oldLevelName}) ----> {$levelTitleID} ({$levelName})</code>\n"
                    . "Time: " . now()->format('Y-m-d H:i:s');

                try {
                    $this->sendMesssageTelegram($telegramMessage);
                } catch (\Exception $e) {
                    Log::error('Telegram error khi cập nhật LevelID: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Cập nhật MucID (cấp bậc đơn hàng) trong tbl_node
     *
     * @param int $userId
     * @param int $mucID 0-5: Cấp bậc đơn hàng
     * @return void
     */
    private function updateMucID($userId, $mucID)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $node = \App\Models\TblNode::where('UserID', $userId)->first();

        if ($node) {
            // Lưu giá trị cũ trước khi update
            $oldMucID = $node->MucID;

            // Validate mucID chỉ nhận 0-5
            $mucID = in_array($mucID, [0, 1, 2, 3, 4, 5]) ? $mucID : 0;

            // Chỉ update và gửi thông báo nếu có thay đổi
            if ($oldMucID != $mucID) {
                $node->update(['MucID' => $mucID]);

                $admin = Auth::guard('admin')->user();
                $adminName = $admin ? ($admin->name ?? $admin->username ?? 'Unknown') : 'System';

                $mucNames = [
                    0 => 'Khách hàng',
                    1 => 'Chuyên nghiệp',
                    2 => 'Gói Đồng',
                    3 => 'Gói Bạc',
                    4 => 'Gói Vàng',
                    5 => 'Gói Kim Cương',
                ];

                $mucName = $mucNames[$mucID] ?? 'N/A';
                $oldMucName = $mucNames[$oldMucID] ?? 'N/A';

                $telegramMessage = "✅ <b>CẬP NHẬT CẤP BẬC ĐƠN HÀNG</b>\n\n"
                    . "Admin: <b>{$adminName}</b>\n"
                    . "UserID: <code>{$user->UserID}</code>\n"
                    . "UserName: <b>{$user->UserName}</b>\n"
                    . "FullName: {$user->FullName}\n"
                    . "Email: {$user->Email}\n"
                    . "MucID: <code>{$oldMucID} ({$oldMucName}) ----> {$mucID} ({$mucName})</code>\n"
                    . "Time: " . now()->format('Y-m-d H:i:s');

                try {
                    $this->sendMesssageTelegram($telegramMessage);
                } catch (\Exception $e) {
                    Log::error('Telegram error khi cập nhật MucID: ' . $e->getMessage());
                }
            }
        }
    }

    public function delete($model)
    {
        try {
            DB::beginTransaction();
            $model->delete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }

    //
    public function groupByStatus()
    {
        $query = User::query()->with('userInfo')->orderBy('UserID', 'DESC')->get();

        return $query->countBy('status');
    }

    public function sendKyc($id)
    {
        try {
            DB::beginTransaction();
            $user = User::with(['userInfo', 'refId'])->findOrFail($id);
            $user->update([
                'email_verified_at' => null,
                'kyc_status' => 'N'
            ]);
            $verifyToken = Str::random(30);
            $userKycLog = UserKycLog::create([
                'user_id' => $id,
                'verify_token' => $verifyToken,
                'type' => KycTypeEnum::EMAIL,
                'status' => KycStatusEnum::SENT
            ]);
            if (! empty($userKycLog)) {
                // Send KYC Email
                $data = $user;
                $data['verify_token'] = $verifyToken;
                $this->sendElasticEmail('KYC Email', $user['email'], 'emails.send-kyc', $data);
                DB::commit();
                return true;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }

    public function getKYCList(Request $request)
    {
        $filters = $request->all();

        // perPage handle
        if (!empty($filters['perPage']) && $filters['perPage'] > 15) {
            $this->perPage = $filters['perPage'];
        }

        return User::query()->with('userInfo')
            ->where('kyc_status', '!=', KycStatusEnum::APPROVED)
            ->orderBy('UserID', 'DESC')->paginate($this->perPage);
    }

    public function saveKycDetail($request, $id)
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            $user = User::with(['userInfo', 'refId'])->findOrFail($id);

            if ($input['kyc_status'] === KycStatusEnum::APPROVED) {
                $input['decline_reason'] = null;
            }

            $result = $user->update([
                'kyc_status' => $input['kyc_status'],
                'decline_reason' => $input['decline_reason']
            ]);

            if ($result) {
                // Send KYC Email
                $data = $user;
                $this->sendElasticEmail('Submit KYC Result', $user['email'], 'emails.approve-kyc', $data);
                DB::commit();
                return true;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
        return false;
    }
}
