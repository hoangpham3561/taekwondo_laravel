<?php

namespace App\Services\UpCustomerCommission;

use App\Models\TblPvUser;
use App\Models\TblNode;
use App\Models\User;
use App\Models\TblTransaction;
use App\Models\TblTransactionLog;
use App\Models\Order;
use App\Models\TblNameCom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram;
use Carbon\Carbon;

class CommissionService
{
    /**
     * Phiên bản Laravel của UpdatePVToCommision
     * Tính hoa hồng từ PV
     * 
     * @param TblPvUser $pvUser
     * @return void
     */
    use Telegram;

    // Thưởng Kết Nối Đại Lý Trả cho chính nó và F1 của nó (nếu mua sỉ) , điều kiên MuID>=1 or LevelID>=1	
    public function updatePVToCommission(TblPvUser $pvUser): void
    {

        // Kiểm tra node tồn tại
        if (!$pvUser->node) {
            $this->sendMesssageTelegram("❌ Lỗi: PVUser ID {$pvUser->ID} chưa có node");
            return;
        }

        $userId = $pvUser->node->UserID ?? null;
        $cnid   = $pvUser->ID;

        if (!$userId) {
            $this->sendMesssageTelegram("❌ Lỗi: PVUser ID {$pvUser->ID} - node không có UserID");
            return;
        }

        $nodeInfo = TblNode::where('UserID', $userId)->first();
        $accInfo  = User::find($userId);

        if (!$nodeInfo || !$accInfo) {
            $this->sendMesssageTelegram("❌ Lỗi tính hoa hồng: UserID = {$userId} hoặc cnid = {$cnid} hoặc nodeInfo = null hoặc accInfo = null");
            return;
        }

        // Lấy F1 info (có thể null nếu user không có F1)
        $f1accInfo = null;
        $f1Node = null;
        if ($accInfo->F1UserID > 0) {
            $f1accInfo = User::find($accInfo->F1UserID);
            $f1Node = TblNode::where('UserID', $accInfo->F1UserID)->first();
        }

        try {
            DB::beginTransaction();
            if ($userId == 1 || $pvUser->isPH > 0) {
                DB::commit();
                return;
            }

            $pv = $pvUser->PV;
            $ismuaSibanle = $pvUser->TypeOrder ?? 0;

            // check duplicated log - check theo user_id và cnid để tránh duplicate
            $existingLog = TblTransactionLog::where('cnid', $cnid)
                ->where('type', 'DL')
                ->where('user_id', $userId)
                ->exists();

            if ($existingLog) {
                DB::commit();
                return;
            }

            $amount1 = 0;
            $amount2 = 0;
            $amount3 = 0;

            // ---- 2. Tính hoa hồng ----
            if ($ismuaSibanle === 0) {
                // Hoa hồng mua sỉ cho người bán
                $order = Order::where('OrderID', $pvUser->OrderID)->first();
                $amount1 = $order->TotalBonus;
                TblTransaction::create([
                    'user_id' => $userId,
                    'user_id2' => $userId,
                    'type' => 'MS',
                    'point' => $amount1,
                    'pv' => $pv,
                    'currency' => 'PA',
                    'note' => "Thưởng mua sỉ  - {$accInfo->UserName} - {$pv} PA",
                    'cnid' => $cnid,
                    'status' => 'Y',
                    'created_at' => $pvUser->DateCreate,
                    'updated_at' => $pvUser->DateCreate,
                ]);


                // Hoa hồng F1
                if ($f1Node && $amount1 > 0 && ($f1Node->MucID >= 1 || $f1Node->LevelID >= 1)) {
                    $amount2 = $pv * 5 / 100;

                    TblTransaction::create([
                        'user_id' => $f1Node->UserID,
                        'user_id2' => $userId,
                        'type' => 'MS',
                        'point' => $amount2,
                        'pv' => $pv,
                        'currency' => 'PA',
                        'note' => "Thưởng mua sỉ 5% - bảo trợ trực tiếp {$accInfo->UserName}",
                        'cnid' => $cnid,
                        'status' => 'Y',
                        'created_at' => $pvUser->DateCreate,
                        'updated_at' => $pvUser->DateCreate,
                    ]);
                }
            } elseif ($ismuaSibanle === 1) {
                // Hoa hồng đại lý
                $amount3 = $pv * 20 / 100;

                if ($f1Node && $amount3 > 0 && ($f1Node->MucID >= 1 || $f1Node->LevelID >= 1)) {
                    $transaction = TblTransaction::create([
                        'user_id'  => $f1Node->UserID,
                        'user_id2' => $userId,
                        'type'     => 'DL',
                        'point'    => $amount3,
                        'pv'       => $pv,
                        'note'     => "Thưởng kết nối đại lý 20% từ - {$accInfo->UserName}",
                        'cnid'     => $cnid,
                        'currency' => 'PA',
                        'status'   => 'Y',
                        'created_at' => $pvUser->DateCreate,
                        'updated_at' => $pvUser->DateCreate,
                    ]);
                    $this->thuongcongsinhIB($f1Node->UserID, $f1Node->UserID, $amount3, $cnid, 'CH', $transaction->id, 1, $pvUser->DateCreate);
                }
            }

            // ---- 3. Log ----
            $f1UserName = $f1accInfo ? $f1accInfo->UserName : '';
            $f1UserId = $f1accInfo ? $f1accInfo->UserID : 0;
            $f1MucID = $f1Node ? $f1Node->MucID : 0;
            $f1LevelID = $f1Node ? $f1Node->LevelID : 0;

            if ($f1accInfo) {
                TblTransactionLog::create([
                    'user_id' => $f1accInfo->UserID,
                    'user_id2' => $userId,
                    'type' => 'DL',
                    'point' => $amount1 + $amount2 + $amount3,
                    'pv' => $pv,
                    'currency' => 'PA',
                    'note' => "Bảo trợ ={$f1UserName} - {$f1UserId} | ismuaSibanle={$ismuaSibanle} | amount1={$amount1}, amount2={$amount2}, amount3={$amount3} | MucID={$f1MucID} - LevelID={$f1LevelID}",
                    'cnid' => $cnid,
                    'status' => 'Y',
                    'created_at' => $pvUser->DateCreate,
                    'updated_at' => $pvUser->DateCreate,
                ]);
            }

            TblTransactionLog::create([
                'user_id' => $userId,
                'user_id2' => $userId,
                'type' => 'DL',
                'point' => $amount1 + $amount2 + $amount3,
                'pv' => $pv,
                'note' => "Bảo trợ ={$f1UserName} - {$f1UserId} | ismuaSibanle={$ismuaSibanle} | amount1={$amount1}, amount2={$amount2}, amount3={$amount3} | MucID={$f1MucID} - LevelID={$f1LevelID}",
                'cnid' => $cnid,
                'currency' => 'PA',
                'status' => 'Y',
                'created_at' => $pvUser->DateCreate,
                'updated_at' => $pvUser->DateCreate,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendMesssageTelegram("❌ Lỗi tính hoa hồng PVUser ID {$pvUser->ID}: " . $e->getMessage());
            return;
        }
        
        // ---- 5. Gọi chia doanh số nếu cần ----
        if ($ismuaSibanle === 1) {
            $this->xetDanhHieu($nodeInfo->NodeID);

            $this->hoahong_dongchia_doanhso($userId, $cnid, $pv,  $pvUser->DateCreate);

            $this->hoahongCuaHang_khong_de_quy($userId, $userId,  $pv, $cnid, $pvUser->DateCreate);

            $this->xetPackage($nodeInfo->NodeID, $cnid);
        }
        $this->dongchiaLanhDao($userId, $cnid, $pv,  $pvUser->DateCreate);

        // ---- 3.5. Thống kê hoa hồng và gửi Telegram ----
        $this->sendCommissionStatistics($cnid, $accInfo->UserName, $accInfo->UserID);
    }

    // Thưởng VIP (ĐỒNG CHIA TRỌN ĐỜI) - TÍNH TRÊN DOANH SỐ TOÀN QUỐC, Cấp nào ăn cấp đó
    protected function hoahong_dongchia_doanhso(int $UserID, int $cnid, int $amount, $dateCreate): void
    {
        if ($amount <= 0) return;

        DB::beginTransaction();

        try {
            $userInfo = User::findOrFail($UserID);

            $vipConfig = [
                'VIP3' => 8,
                'VIP2' => 6,
                'VIP1' => 4,
            ];

            // 1️⃣ Lấy danh sách node + phân loại VIP ngay tại SQL
            $vipNodes = TblNode::select([
                'UserID',
                'MucID',
                'LevelID',
                DB::raw("
                CASE
                    WHEN MucID = 5 OR LevelID = 3 THEN 'VIP3'
                    WHEN MucID = 4 OR LevelID = 2 THEN 'VIP2'
                    WHEN MucID = 3 OR LevelID = 1 THEN 'VIP1'
                END AS VipLevel
            ")
            ])
                ->whereRaw("(MucID >= 3 OR LevelID >= 1)")
                ->whereNotNull(DB::raw("
            CASE
                WHEN MucID >= 3 OR LevelID >= 1 THEN 1
            END
        "))
                ->get()
                ->groupBy('VipLevel');

            // 2️⃣ Lấy user đã có log để tránh duplicate
            $existing = TblTransactionLog::where('cnid', $cnid)
                ->where('type', 'VIP')
                ->pluck('user_id')
                ->toArray();

            $transactions = [];
            $logs = [];

            foreach ($vipConfig as $vipName => $percent) {

                $nodes = $vipNodes->get($vipName, collect());
                if ($nodes->isEmpty()) continue;

                $eligible = $nodes->reject(fn($n) => in_array($n->UserID, $existing));
                if ($eligible->isEmpty()) continue;

                $totalPeople = $eligible->count();
                $totalAmount = $amount * $percent / 100;
                $amountPerNode = $totalAmount / $totalPeople;

                $note = "Hoa hồng đồng chia doanh số {$percent}% ($vipName) của {$userInfo->UserName} - $amount";

                foreach ($eligible as $n) {
                    $transactions[] = [
                        'user_id' => $n->UserID,
                        'user_id2' => $UserID,
                        'type' => 'VIP',
                        'point' => $amountPerNode,
                        'pv' => $amount,
                        'note' => $note,
                        'currency' => 'PA',
                        'cnid' => $cnid,
                        'created_at' => $dateCreate ?? now(),
                        'updated_at' => $dateCreate ?? now(),
                        'trans_id' => 0,
                        'status' => 'Y',
                    ];

                    $logs[] = [
                        'user_id' => $n->UserID,
                        'user_id2' => $UserID,
                        'type' => 'VIP',
                        'point' => $amountPerNode,
                        'pv' => $amount,
                        'currency' => 'PA',
                        'percent' => $percent,
                        'note' => "$totalPeople users - $vipName",
                        'cnid' => $cnid,
                        'created_at' => $dateCreate ?? now(),
                        'updated_at' => $dateCreate ?? now(),
                        'trans_id' => 0,
                        'status' => 'Y',
                    ];
                }

                // $this->sendMesssageTelegram(
                //     "Đồng chia doanh số - $vipName {$amount} PA - {$percent}% - $totalPeople users nhận: "
                //         . number_format($amountPerNode, 2)
                //         . " - Tổng: " . number_format($totalAmount, 2)
                // );
            }

            // 3️⃣ Batch insert
            if (!empty($transactions)) {
                TblTransaction::insert($transactions);

                // Sau khi insert, query lại để lấy ID và gọi thuongcongsinhIB
                // Lấy các transaction vừa insert (dựa vào cnid, type, created_at)
                $insertedTransactions = TblTransaction::where('cnid', $cnid)
                    ->where('type', 'VIP')
                    ->where('created_at', '>=', $dateCreate ?? now())
                    ->get();

                // Gọi thuongcongsinhIB cho mỗi transaction VIP
                foreach ($insertedTransactions as $transaction) {
                    $this->thuongcongsinhIB(
                        $transaction->user_id,      // userId: người nhận hoa hồng
                        $transaction->user_id,     // fUserId: người tạo giao dịch
                        (int)$transaction->point,      // amount: point
                        $transaction->cnid,         // cnid
                        'CH',                       // type: Cộng sinh
                        $transaction->id,
                        1,                          // floor: bắt đầu từ tầng 1
                        $transaction->created_at    // dateCreate
                    );
                }
            }
            if (!empty($logs)) TblTransactionLog::insert($logs);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendMesssageTelegram("❌ Lỗi hoa hồng đồng chia doanh số: " . $e->getMessage());
        }
    }

    // CỘNG HƯỞNG CỘNG SINH MATCHING  - TÍNH TRÊN Thưởng VIP, Thưởng Kết Nối Đại Lý
    public function thuongcongsinhIB(
        int $userId,
        int $fUserId,
        float $amount,
        int $cnid,
        string $type = 'CH',
        int $transactionId,
        int $floor,
        $dateCreate
    ): void {

        if ($userId === 0) {
            return;
        }
        $fUserInfo  = User::find($fUserId);
        $dateCreate = $dateCreate ?: now();

        // % theo tầng
        $percentTable = [
            1 => 5,
            2 => 3,
            3 => 2,
        ];

        // Lấy chain F1 tối đa 3 tầng
        $chain = [];
        $current = User::find($userId);
        if (!$current) {

            return;
        }


        for ($i = 1; $i <= 3; $i++) {
            if (!$current || $current->F1UserID == 0) break;
            $chain[$i] = $current->F1UserID;
            $current = User::find($current->F1UserID);
        }

        foreach ($chain as $floor => $fUser) {

            $node = TblNode::where('UserID', $fUser)->first();
            if (!$node) continue;

            $percent = $percentTable[$floor] ?? 0;

            if ($node->MucID < 1 && $node->LevelID < 1) {
                $percent = 0;
            }

            $finalAmount = $percent > 0 ? ($amount * $percent / 100) : 0;

            try {
                DB::beginTransaction();

                $exists = TblTransactionLog::where([
                    'user_id'  => $fUser,
                    'cnid'     => $cnid,
                    'type'     => $type,
                    'trans_id' => $transactionId
                ])->exists();

                if (!$exists) {

                    if ($finalAmount > 0) {
                        TblTransaction::create([
                            'user_id'    => $fUser,
                            'user_id2'   => $fUserId,
                            'type'       => $type,
                            'point'      => $finalAmount,
                            'pv'         => $amount,
                            'floor'      => $floor,
                            'currency' => 'PA',
                            'note'       => "Cộng sinh  {$percent}% floor {$floor} từ {$fUserInfo->UserName} ",
                            'cnid'       => $cnid,
                            'trans_id'   => $transactionId,
                            'status'     => 'Y',
                            'created_at' => $dateCreate,
                            'updated_at' => $dateCreate,
                        ]);
                    }

                    TblTransactionLog::create([
                        'user_id'    => $fUser,
                        'user_id2'   => $fUserId,
                        'type'       => $type,
                        'point'      => $finalAmount,
                        'pv'         => $amount,
                        'percent'    => $percent,
                        'note'       => "cộng sinh - Floor {$floor} - MucID {$node->MucID} -{$fUserInfo->UserName}",
                        'currency' => 'PA',
                        'cnid'       => $cnid,
                        'trans_id'   => $transactionId,
                        'status'     => 'Y',
                        'created_at' => $dateCreate,
                        'updated_at' => $dateCreate,
                    ]);
                }


                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->sendMesssageTelegram("❌ Thưởng cộng sinh Lỗi DB tạo transaction: cnid {$cnid} - user_id {$fUser} - " . $e->getMessage());
                Log::error("Lỗi IB Bonus: " . $e->getMessage());
            }
        }
    }

    // HOA HỒNG LÃNH ĐẠO - ĐỒNG CHIA Phó giám đốc kinh doanh
    protected function dongchiaLanhDao(int $UserID, int $cnid, int $amount,  $dateCreate): void
    {
        if ($amount <= 0) return;

        DB::beginTransaction();

        try {
            $userInfo = User::findOrFail($UserID);
            $pvUser = TblPvUser::findOrFail($cnid);
            $vipNodes = TblNode::where('LevelID', '=', 3)
                ->get();

            $percent = $pvUser->TypeOrder == 0 ? 5 : 3;
            $totalPeople = $vipNodes->count();

            if ($totalPeople <= 0) return;
            $amountPerNode = $amount * $percent / 100 / $totalPeople;

            // $this->sendMesssageTelegram(
            //     "Đồng chia lãnh đạo - {$amount} PA - {$percent}% - $totalPeople users nhận: "
            //         . number_format($amountPerNode, 2)
            // );

            $transactions = [];
            $logs = [];

            foreach ($vipNodes as $node) {

                $existingLog = TblTransactionLog::where('cnid', $cnid)
                    ->where('type', 'LD')
                    ->exists();

                if ($existingLog) continue;

                $transactions[] = [
                    'user_id' => $node->UserID,
                    'user_id2' => $UserID,
                    'type' => 'LD',
                    'point' => $amountPerNode,
                    'pv' => $amount,
                    'currency' => 'PA',
                    'note' => "Đồng chia lãnh đạo {$percent}% - từ {$userInfo->UserName} - {$amount}",
                    'cnid' => $cnid,
                    'created_at' => $dateCreate ?? now(),
                    'updated_at' => $dateCreate ?? now(),
                    'trans_id' => 0,
                    'status' => 'Y',
                ];

                $logs[] = [
                    'user_id' => $node->UserID,
                    'user_id2' => $UserID,
                    'type' => 'LD',
                    'point' => $amountPerNode,
                    'pv' => $amount,
                    'currency' => 'PA',
                    'percent' => $percent,
                    'note' => "Đồng chia lãnh đạo {$percent}% - {$userInfo->UserName} - {$amount} - {$totalPeople} users nhận: " . number_format($amountPerNode, 2),
                    'cnid' => $cnid,
                    'created_at' => $dateCreate ?? now(),
                    'updated_at' => $dateCreate ?? now(),
                    'trans_id' => 0,
                    'status' => 'Y',
                ];
            }

            // 3️⃣ Batch insert
            if (!empty($transactions)) TblTransaction::insert($transactions);
            if (!empty($logs)) TblTransactionLog::insert($logs);

            // $totalAmount = $amount * $percent / 100;
            // $this->sendMesssageTelegram(
            //     "Đồng chia lãnh đạo - {$amount} PA - {$percent}% - $totalPeople users nhận: "
            //         . number_format($amountPerNode, 2)
            //         . " - Tổng: " . number_format($totalAmount, 2)
            // );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendMesssageTelegram("❌ Lỗi hoa hồng đồng chia lãnh đạo: " . $e->getMessage());
        }
    }

    /**
     * Tính hoa hồng Matching Bonus (đệ quy theo F1)
     * Phiên bản Laravel của hàm matching
     * 
     * @param int $userID UserID hiện tại
     * @param int $fUserID UserID gốc (người tạo giao dịch)
     * @param int $indirectID Mức độ gián tiếp (bắt đầu từ 1)
     * @param float $pv Số PV
     * @param int $cnid CNID (PVUser ID)
     * @return void
     */


    protected function hoahongCuaHang_khong_de_quy(
        int $startUserID,
        int $fUserID,
        float $pv,
        int $cnid,
        $dateCreate = null
    ): void {
        if ($startUserID <= 0 || $fUserID <= 0 || $cnid <= 0 || $pv <= 0) {
            return;
        }

        $dateCreate = $dateCreate ? Carbon::parse($dateCreate) : now();

        DB::transaction(function () use ($startUserID, $fUserID, $pv, $cnid, $dateCreate) {

            /**
             * 1. Lấy tuyến F1 bằng CTE (F1, F1 của F1, ...) – tối đa 200 tầng
             */
            $lineage = DB::select("
            WITH RECURSIVE lineage AS (
                -- Tầng 1: F1 của startUser
                SELECT u.F1UserID AS UserID, 1 AS depth
                FROM user u
                WHERE u.UserID = :startUserID

                UNION ALL

                -- Tầng tiếp theo: F1 của user ở tầng trước
                SELECT u.F1UserID AS UserID, l.depth + 1 AS depth
                FROM user u
                INNER JOIN lineage l ON u.UserID = l.UserID
                WHERE l.UserID IS NOT NULL
                  AND l.depth < 100
            )
            SELECT UserID, depth
            FROM lineage
            WHERE UserID IS NOT NULL
            ORDER BY depth ASC
        ", [
                'startUserID' => $startUserID,
            ]);

            if (empty($lineage)) {
                return;
            }

            $ancestorIDs = [];
            $depthByUser = [];

            foreach ($lineage as $row) {
                $uid = (int) $row->UserID;
                if ($uid <= 0) {
                    continue;
                }
                $ancestorIDs[] = $uid;
                $depthByUser[$uid] = (int) $row->depth;
            }

            if (empty($ancestorIDs)) {
                return;
            }

            //trả về danh sách các tầng từ F1 đến user gốc
            $ancestorIDs = array_values(array_unique($ancestorIDs));
            //Log::info("ancestorIDs", ['ancestorIDs' => $ancestorIDs]);
            /**
             * 2. Prefetch node + user cho toàn bộ tuyến
             */
            $nodes = TblNode::whereIn('UserID', $ancestorIDs)
                ->get()
                ->keyBy('UserID');

            $users = User::whereIn('UserID', array_merge($ancestorIDs, [$fUserID]))
                ->get()
                ->keyBy('UserID');

            $fromUser = $users[$fUserID] ?? null;
            if (!$fromUser) {
                return;
            }

            /**
             * 3. Lấy max percent hiện tại cho cnid này (từ database)
             */
            $initialMaxPercent = TblTransactionLog::where([
                'cnid' => $cnid,
                'type' => 'TL',
            ])
                ->max('percent');

            $initialMaxPercent = $initialMaxPercent ? (float) $initialMaxPercent : 0.0;

            /**
             * 4. Lấy danh sách user đã có log CH với cnid này để không tính lại
             */
            $existingLogsByUser = TblTransactionLog::where('cnid', $cnid)
                ->where('type', 'TL')
                ->whereIn('user_id', $ancestorIDs)
                ->pluck('id', 'user_id')
                ->toArray();

            /**
             * 5. Chuẩn bị batch insert
             */
            $logsToInsert         = [];
            $transactionsToInsert = [];
            $pvIncrementUserIDs   = [];

            // QUAN TRỌNG: Track tổng percent đã phân phối trong lần chạy này
            $totalPercentDistributed = $initialMaxPercent;

            foreach ($ancestorIDs as $userID) {
                $floor = $depthByUser[$userID] ?? null;
                if (!$floor || $floor > 200) {
                    continue;
                }

                $node = $nodes[$userID] ?? null;
                $user = $users[$userID] ?? null;

                if (!$node || !$user) {
                    continue;
                }
                $this->xetDanhHieu($node->NodeID);
                // Nếu đã có log CH cho cnid này + user này → bỏ qua
                $alreadyLogged = isset($existingLogsByUser[$userID]);

                $percent = 0.0;
                $amount  = 0.0;
                $noteCondition = '';

                if (!$alreadyLogged) {

                    // Điều kiện cộng PVSystem
                    if ($node->MucID >= 1) {
                        $pvIncrementUserIDs[$userID] = true;
                    }

                    // Điều kiện đủ hưởng hoa hồng
                    $eligible = ($node->RankID >= 1 && ($node->LevelID >= 3 || $node->MucID >= 5));

                    if ($eligible) {
                        // SỬA LẠI: Tính percent dựa trên tổng percent đã phân phối
                        // Tổng tối đa là 5%, nên percent còn lại = 5 - totalPercentDistributed
                        $percent = max(0, 5 - $totalPercentDistributed);

                        if ($percent > 0) {
                            $amount = $percent * $pv / 100;

                            if ($amount > 0) {
                                $transactionsToInsert[] = [
                                    'user_id'    => $userID,
                                    'user_id2'   => $fUserID,
                                    'type'       => 'TL',
                                    'point'      => $amount,
                                    'pv'         => $pv,
                                    'note'       => "Hoa hồng cửa hàng {$percent}% từ {$fromUser->UserName} {$pv} tầng {$floor}",
                                    'cnid'       => $cnid,
                                    'currency'   => 'PA',
                                    'trans_id'   => 0,
                                    'status'     => 'Y',
                                    'created_at' => $dateCreate,
                                    'updated_at' => $dateCreate,
                                ];
                            }

                            // Cập nhật tổng percent đã phân phối
                            $totalPercentDistributed += $percent;
                        }
                    } else {
                        $noteCondition =
                            'Không đủ điều kiện: RankID ' . $node->RankID .
                            ' >= 1, LevelID ' . $node->LevelID .
                            ' >= 3, MucID ' . $node->MucID . ' >= 5. ';
                    }

                    // Log: luôn ghi lại để trace, kể cả amount = 0
                    $logsToInsert[] = [
                        'user_id'    => $userID,
                        'user_id2'   => $fUserID,
                        'type'       => 'TL',
                        'point'      => $amount,
                        'pv'         => $pv,
                        'percent'    => $percent,
                        'currency'   => 'PA',
                        'note'       => $noteCondition .
                            "HH cửa hàng {$percent}% từ {$fromUser->UserName} - {$pv} tầng {$floor} | % ban đầu: {$initialMaxPercent}, % đã phân phối: {$totalPercentDistributed}",
                        'cnid'       => $cnid,
                        'floor'      => $floor,
                        'trans_id'   => 0,
                        'status'     => 'Y',
                        'created_at' => $dateCreate,
                        'updated_at' => $dateCreate,
                    ];
                }
            }

            /**
             * 6. Thực thi batch update/insert
             */

            // Cộng PVSystem cho tất cả node đủ điều kiện
            if (!empty($pvIncrementUserIDs)) {
                TblNode::whereIn('UserID', array_keys($pvIncrementUserIDs))
                    ->update([
                        'PVSystem' => DB::raw("PVSystem + " . (float) $pv),
                    ]);
            }

            // Insert transaction
            if (!empty($transactionsToInsert)) {
                TblTransaction::insert($transactionsToInsert);
            }

            // Insert log
            if (!empty($logsToInsert)) {
                TblTransactionLog::insert($logsToInsert);
            }
        });
    }


    // ==== Hàm nhỏ gọn để tính phần trăm từ MucID ====
    private function percentFromMuc(int $muc): int
    {
        return [1 => 20, 2 => 25, 3 => 30, 4 => 35, 5 => 40][$muc] ?? 0;
    }

    private function percentFromLevel(int $level): int
    {
        return [1 => 30, 2 => 35, 3 => 40][$level] ?? 0;
    }


    /**
     * Xét package và cập nhật MucID nếu cần
     * Phiên bản Laravel của Xet_Package
     * 
     * @param int $nodeId
     * @param int $cnid
     * @return void
     */
    public function xetPackage(int $nodeId, int $cnid): void
    {
        $nodeInfo = TblNode::find($nodeId);
        if (!$nodeInfo) {
            return;
        }
        $totalPv = TblPvUser::where('NodeID', $nodeId)
            ->where('TypeOrder', 1)
            ->sum('PV');

        $newMucID = $nodeInfo->MucID;
        if ($totalPv >= 46800 && $nodeInfo->MucID < 5) {
            $newMucID = 5;
        } elseif ($totalPv >= 23400 && $nodeInfo->MucID < 4) {
            $newMucID = 4;
        } elseif ($totalPv >= 4680 && $nodeInfo->MucID < 3) {
            $newMucID = 3;
        } elseif ($totalPv >= 2340 && $nodeInfo->MucID < 2) {
            $newMucID = 2;
        } elseif ($totalPv >= 468 && $nodeInfo->MucID < 1) {
            $newMucID = 1;
        }

        if ($newMucID != $nodeInfo->MucID || $nodeInfo->TotalPV != $totalPv) {
            $nodeInfo->update([
                'MucID' => $newMucID,
                'TotalPV' => $totalPv
            ]);
        }
    }
    protected function xetDanhHieu(int $nodeId): void
    {
        $nodeInfo = TblNode::find($nodeId);

        if (!$nodeInfo) {
            return;
        }

        // Nếu đã đạt LevelID = 3 thì không cần kiểm tra nữa
        if ($nodeInfo->LevelID >= 3) {
            return;
        }

        $userId = $nodeInfo->UserID;
        $currentLevelID = $nodeInfo->LevelID;
        $newLevelID = $currentLevelID; // Bắt đầu từ LevelID hiện tại

        // 1. Kiểm tra LevelID = 1 (Giám Sát Kinh Doanh)
        // Có 5 user được bảo trợ trực tiếp có MucID >= 1
        if ($currentLevelID < 1) {
            $f1UsersWithMucID = User::where('F1UserID', $userId)
                ->join('tbl_node', 'user.UserID', '=', 'tbl_node.UserID')
                ->where('tbl_node.MucID', '>=', 1)
                ->distinct('user.UserID')
                ->count('user.UserID');

            if ($f1UsersWithMucID >= 5) {
                $newLevelID = 1;
            }
        }

        // 2. Kiểm tra LevelID = 2 (Quản Lý Kinh Doanh)
        // Có 5 user ở 5 nhánh khác nhau có LevelID >= 1
        if ($newLevelID < 2) {
            $branchesWithLevel1 = $this->countBranchesWithLevel($nodeId, 1);
            if ($branchesWithLevel1 >= 5) {
                $newLevelID = 2;
            }
        }

        // 3. Kiểm tra LevelID = 3 (Phó Giám Đốc Kinh Doanh)
        // Có 5 user ở 5 nhánh khác nhau có LevelID >= 2
        if ($newLevelID < 3) {
            $branchesWithLevel2 = $this->countBranchesWithLevel($nodeId, 2);
            if ($branchesWithLevel2 >= 5) {
                $newLevelID = 3;
            }
        }

        // Cập nhật LevelID nếu có thay đổi
        if ($newLevelID > $currentLevelID) {
            $nodeInfo->update([
                'LevelID' => $newLevelID,
                'DateUpLevel' => now(),
            ]);

            // Refresh nodeInfo sau khi update
            $nodeInfo->refresh();

            $userInfo = User::find($nodeInfo->UserID);
            // Gửi thông báo Telegram
            $telegramMessage = "🎉 <b>THĂNG CẤP MỚI</b>\n\n"
                . "UserID: <code>{$userId}</code>\n"
                . "UserName: <b>{$userInfo->UserName}</b>\n"
                . "LevelID cũ: <code>{$currentLevelID}</code>\n"
                . "LevelID mới: <b>{$newLevelID}</b>\n"
                . "Thời gian: " . now()->format('Y-m-d H:i:s');
            $this->sendMesssageTelegram($telegramMessage);

            Log::info('XetDanhHieu: Thăng cấp thành công', [
                'user_id' => $userId,
                'node_id' => $nodeId,
                'old_level' => $currentLevelID,
                'new_level' => $newLevelID,
            ]);
        }

        if ($nodeInfo->UserID == 1) {
            return;
        }

        // $userInfo = User::find($nodeInfo->UserID);
        // if ($userInfo && $userInfo->F1UserID > 0) {
        //     // Sửa: Lấy NodeID của F1 user thay vì truyền UserID
        //     $f1Node = TblNode::where('UserID', $userInfo->F1UserID)->first();
        //     if ($f1Node) {
        //         $this->xetDanhHieu($f1Node->NodeID);
        //     }
        // }
    }

    /**
     * Đếm số nhánh khác nhau có user với LevelID >= minLevel
     * Mỗi nhánh = 1 user F1 (bảo trợ trực tiếp)
     * Trong mỗi nhánh, tìm đệ quy các user con cháu theo F1UserID
     * 
     * @param int $nodeId NodeID gốc
     * @param int $minLevel LevelID tối thiểu
     * @return int Số nhánh
     */
    protected function countBranchesWithLevel(int $nodeId, int $minLevel): int
    {
        $nodeInfo = TblNode::find($nodeId);
        if (!$nodeInfo) {
            return 0;
        }

        $userId = $nodeInfo->UserID;

        // Lấy tất cả các user F1 (bảo trợ trực tiếp) - mỗi F1 là 1 nhánh
        $f1Users = User::where('F1UserID', $userId)->get();

        if ($f1Users->isEmpty()) {
            return 0;
        }

        $validBranches = 0;

        // Kiểm tra từng nhánh (mỗi F1 là 1 nhánh)
        foreach ($f1Users as $f1User) {
            // Tìm trong nhánh này (bao gồm F1 và tất cả các user con cháu) có user nào có LevelID >= minLevel
            if ($this->hasUserWithLevelInBranch($f1User->UserID, $minLevel)) {
                $validBranches++;

                // Nếu đã đủ 5 nhánh thì dừng
                if ($validBranches >= 5) {
                    break;
                }
            }
        }

        return $validBranches;
    }

    /**
     * Kiểm tra trong nhánh (từ F1UserID trở xuống) có user nào có LevelID >= minLevel
     * Tìm đệ quy theo F1UserID trong bảng user
     * 
     * @param int $f1UserId UserID của F1 (đầu nhánh)
     * @param int $minLevel LevelID tối thiểu
     * @return bool
     */
    protected function hasUserWithLevelInBranch(int $f1UserId, int $minLevel): bool
    {
        // Kiểm tra F1 có LevelID >= minLevel không
        $f1Node = TblNode::where('UserID', $f1UserId)->first();
        if ($f1Node && $f1Node->LevelID >= $minLevel) {
            return true;
        }

        // Đệ quy tìm trong các user con cháu (F2, F3, ...) của F1
        // Tìm các user có F1UserID = f1UserId
        $descendants = User::where('F1UserID', $f1UserId)->get();

        foreach ($descendants as $descendant) {
            // Kiểm tra user này
            $descendantNode = TblNode::where('UserID', $descendant->UserID)->first();
            if ($descendantNode && $descendantNode->LevelID >= $minLevel) {
                return true;
            }

            // Đệ quy tìm trong các user con cháu của user này
            if ($this->hasUserWithLevelInBranch($descendant->UserID, $minLevel)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Thống kê hoa hồng từ tbl_transactions với CNID và gửi Telegram
     * 
     * @param int $cnid CNID (PVUser ID)
     * @param string $userName Tên user
     * @param int $userId UserID
     * @return void
     */
    public function sendCommissionStatistics(int $cnid, string $userName, int $userId): void
    {
        try {
            // Lấy tất cả transactions với CNID, sử dụng Eloquent relationship
            $transactions = TblTransaction::with('nameCom')
                ->where('cnid', $cnid)
                ->get();

            // Log để debug
            Log::info("sendCommissionStatistics - CNID: {$cnid}, Transactions count: " . $transactions->count());

            if ($transactions->isEmpty()) {
                // Gửi thông báo nếu không có transaction
                $message = "📊 <b>THỐNG KÊ HOA HỒNG</b>\n\n";
                $message .= "👤 User: <b>{$userName}</b> (ID: {$userId})\n";
                $message .= "🔢 CNID: <code>{$cnid}</code>\n\n";
                $message .= "⚠️ <b>Chưa có giao dịch hoa hồng nào</b>\n";
                $message .= "• Thời gian: " . now()->format('d/m/Y H:i:s');
                $this->sendMesssageTelegram($message);
                return;
            }

            // Group by type và tính toán thống kê bằng Collection
            $statistics = $transactions->groupBy('type')->map(function ($group, $type) {
                $nameCom = $group->first()->nameCom;
                return [
                    'type' => $type,
                    'name' => $nameCom ? $nameCom->name : null,
                    'count' => $group->count(),
                    'total_point' => $group->sum('point'),
                    'total_pv' => $group->sum('pv'), // Thêm total_pv vào map
                ];
            })->sortBy('type')->values();

            if ($statistics->isEmpty()) {
                Log::warning("sendCommissionStatistics - CNID: {$cnid}, Statistics empty after grouping");
                return;
            }

            // Tính tổng
            $totalPoint = $statistics->sum('total_point');
            $totalPv = $statistics->sum('total_pv'); // Sửa lại từ 'total_pv'

            // Format tin nhắn Telegram
            $message = "📊 <b>THỐNG KÊ HOA HỒNG</b>\n\n";
            $message .= "👤 Người mua hàng: <b>{$userName}</b> (ID: {$userId})\n";
            $message .= "🔢 CNID: <code>{$cnid}</code>\n\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";

            foreach ($statistics as $stat) {
                $typeName = $stat['name'] ?: $stat['type'];
                $message .= "• <b>{$typeName}</b> ({$stat['type']})\n";
                $message .= "  └ Tổng số ngừoi hưởng: {$stat['count']}\n";
                $message .= "  └ Tổng điểm: " . number_format($stat['total_point'], 2) . " PA\n";
                $message .= "\n";
            }

            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "💰 <b>TỔNG CỘNG:</b>\n";
            $message .= "• Tổng hoa hồng: <b>" . number_format($totalPoint, 2) . " PA</b>\n";

            $message .= "• Thời gian: " . now()->format('d/m/Y H:i:s');

            // Gửi Telegram
            $this->sendMesssageTelegram($message);
            //Log::info("sendCommissionStatistics - CNID: {$cnid}, Message sent successfully");
        } catch (\Exception $e) {
            Log::error("Lỗi thống kê hoa hồng CNID {$cnid}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Gửi thông báo lỗi
            $errorMessage = "❌ <b>LỖI THỐNG KÊ HOA HỒNG</b>\n\n";
            $errorMessage .= "🔢 CNID: <code>{$cnid}</code>\n";
            $errorMessage .= "👤 User: <b>{$userName}</b> (ID: {$userId})\n";
            $errorMessage .= "⚠️ Lỗi: " . $e->getMessage();
            $this->sendMesssageTelegram($errorMessage);
        }
    }
}
