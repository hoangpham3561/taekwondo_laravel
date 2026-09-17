<?php

namespace App\Traits;

use App\Models\User;
use App\Models\TblNodeDownlineLogF1;
use Illuminate\Support\Facades\DB;

/**
 * Trait để xử lý upgrade F1 indirect cho user
 * Sử dụng để tăng TotalMember trong tbl_node và tạo log downline
 */
trait UpgradeF1Indirect
{
    /**
     * Hàm upgrade_f1_indirect - chuyển đổi từ PHP thuần sang Laravel
     * 
     * @param int $UserID UserID của user cần xử lý
     * @param int $FUserID UserID của user gốc (user mới đăng ký)
     * @param int $IndirectID Mức độ gián tiếp (bắt đầu từ 1)
     * @param \Carbon\Carbon|string $DateCreate Ngày tạo
     * @return void
     */
    protected function upgrade_f1_indirect($UserID, $FUserID, $IndirectID = 1, $DateCreate)
    {
        // Kiểm tra điều kiện dừng: UserID không hợp lệ hoặc IndirectID vượt quá 500
        if (intval($UserID) == 0 || $UserID == '' || $IndirectID > 500) {
            return;
        }

        // Lấy thông tin node của user
        $NodeInfo = User::where('UserID', $UserID)->first();
        if (!$NodeInfo) {
            return;
        }

        // Kiểm tra xem đã có log chưa
        $log = TblNodeDownlineLogF1::where('FUserID', $FUserID)
            ->where('UserID', $NodeInfo->F1UserID)
            ->first();

        // Nếu chưa có log và F1UserID hợp lệ (> 0)
        if (!$log && $NodeInfo->F1UserID > 0) {
            // Tăng TotalMember trong tbl_node
            DB::table('tbl_node')
                ->where('UserID', $NodeInfo->F1UserID)
                ->increment('TotalMember', 1);

            // Tạo log downline F1
            TblNodeDownlineLogF1::create([
                'UserID' => $NodeInfo->F1UserID,
                'FUserID' => $FUserID,
                'IndirectID' => $IndirectID,
                'DateCreate' => $DateCreate,
                'Type' => '',
                'CNID' => 0,
            ]);
        }

        // Đệ quy nếu F1UserID > 0 để tiếp tục xử lý cấp trên
        if ($NodeInfo->F1UserID > 0) {
            $this->upgrade_f1_indirect($NodeInfo->F1UserID, $FUserID, $IndirectID + 1, $DateCreate);
        }
    }
}
