<?php

namespace App\Http\Controllers;

use App\Services\UpCustomerCommission\NodeService;  // Thay đổi từ App\Services\NodeService
use App\Services\UpCustomerCommission\CommissionService;
use App\Models\User;
use App\Models\TblNode;
use App\Models\TblPvUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\UpgradeF1Indirect;
use Illuminate\Support\Facades\DB;
use App\Models\TblTransaction;

class TestNodeController extends Controller
{
    use UpgradeF1Indirect;
    protected NodeService $nodeService;
    protected CommissionService $commissionService;

    public function __construct(NodeService $nodeService, CommissionService $commissionService)
    {
        $this->nodeService = $nodeService;
        $this->commissionService = $commissionService;
    }

    /**
     * Test endpoint: Tạo user và lên cây nhị phân
     * 
     * Tạo user con: http://127.0.0.1:8000/api/test/node-tree?referrer_user_id=1&pos=L&username=child1&email=child1@test.com&nguoimuahang=1&typeOrder=0&pv=1000
     */
    public function createUserAndPlaceInTree(Request $request): JsonResponse
    {
        DB::enableQueryLog();

        $start = microtime(true);

        // 1) EAGER LOADING
        $users_eager = User::with('node', 'transactions')->get();

        $eager_time = microtime(true) - $start;
        $eager_queries = DB::getQueryLog();
        DB::flushQueryLog();



        // 2) TỰ VIẾT 3 QUERY THỦ CÔNG
        DB::enableQueryLog();
        $start2 = microtime(true);

        $users = User::get();
        $nodes = TblNode::whereIn('UserID', $users->pluck('UserID'))->get()->keyBy('UserID');
        $transactions = TblTransaction::whereIn('user_id', $users->pluck('UserID'))->get()->groupBy('user_id');

        // manual attach (bắt buộc phải làm nếu không bị lazy load thêm query)
        foreach ($users as $u) {
            $u->node = $nodes[$u->UserID] ?? null;
            $u->transactions = $transactions[$u->UserID] ?? collect();
        }

        $manual_time = microtime(true) - $start2;
        $manual_queries = DB::getQueryLog();
        DB::flushQueryLog();



        // 3) LAZY LOADING (CHO BẠN THẤY N+1)
        DB::enableQueryLog();
        $start3 = microtime(true);

        $lazy_users = User::all();
        foreach ($lazy_users as $u) {
            $u->node;          // mỗi lần 1 query
            $u->transactions;  // mỗi lần 1 query
        }

        $lazy_time = microtime(true) - $start3;
        $lazy_queries = DB::getQueryLog();



        return response()->json([
            "Eager Loading" => [
                "time" => $eager_time,
                "query_count" => count($eager_queries),
                "queries" => $eager_queries,
            ],

            "Manual 3 query" => [
                "time" => $manual_time,
                "query_count" => count($manual_queries),
                "queries" => $manual_queries,
            ],

            "Lazy loading (N+1)" => [
                "time" => $lazy_time,
                "query_count" => count($lazy_queries),
            ],
        ]);

        dd($transaction);
        // $this->commissionService->thuongcongsinhIB(32, 32, 100, 54, 'DL', 15, 1, now());
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Thuởng cộng hưởng thành công',
        // ], 200);

        // 1. Lấy params
        $referrerUserId = $request->input('referrer_user_id', 0);
        $pos = $request->input('pos', 'A');
        $username = '1111111111';
        $email = $request->input('email', 'test_' . uniqid() . '@test.com');

        $referrerUserId = $referrerUserId == 0 ? 1 : $referrerUserId;
        $nguoimuahang = $request->input('nguoimuahang', 0);
        $typeOrder = $request->input('typeOrder', 0);
        $pv = $request->input('pv', default: 0);
        if ($nguoimuahang) {
            $pvUser = TblPvUser::create([
                'NodeID' => $nguoimuahang,
                'PV' => $pv,
                'PVpayBack' => 0,
                'PVProfit' => 0,
                'Profit' => 0,
                'Period' => 18,
                'OrderID' => 1,
                'LevelID' => 0,
                'TypeOrder' => $typeOrder,
                'DateCreate' => now(),
                'MaintainDateCreate' => now(),
                'Currency' => '0',
                'Symbol' => 'USDT',
                'Status' => 'Y',
                'Maintain' => 'N',
                'isPH' => 0,
                'IsApp' => 'N',
                'IsCC' => 'N',
                'isQL' => 0,
                'isShared' => 0,
                'ProductID' => 5,
                'MaintainLeader' => 'N',
            ]);
        }
        // 3. Kiểm tra referrer có node chưa
        if (!TblNode::where('UserID', $referrerUserId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Referrer chua co node trong cay',
            ], 400);
        }

        // 4. Insert vào bảng user
        $newUser = $this->createUserWithDefaults([
            'UserName' => $username,
            'Email' => $email,
            'ReferrerUserID' => $referrerUserId,
            'F1UserID' => $referrerUserId,
            'Pos' => $pos,
        ], $request);
        if ($newUser->UserID > 0) {
            $newUser->update([
                'UserName' => $newUser->UserID
            ]);
            $this->upgrade_f1_indirect($newUser->UserID, $newUser->UserID, 1, now());
        }

        // 5. Gọi reUpdActCustomer để lên cây
        try {
            $this->nodeService->reUpdActCustomer($newUser->UserID);

            $node = TblNode::where('UserID', $newUser->UserID)->first();

            $orderId = $request->input('order_id', 0);
            $productId = $request->input('product_id', 1);

            if ($node && $pv > 0) {
                $pvUser = TblPvUser::create([
                    'NodeID' => $node->NodeID,
                    'PV' => $pv,
                    'PVpayBack' => 0,
                    'PVProfit' => 0,
                    'Profit' => 0,
                    'Period' => 18,
                    'TypeOrder' => $typeOrder,
                    'OrderID' => $orderId,
                    'LevelID' => 0,
                    'DateCreate' => now(),
                    'MaintainDateCreate' => now(),
                    'Currency' => '0',
                    'Symbol' => 'USDT',
                    'Status' => 'Y',
                    'Maintain' => 'N',
                    'isPH' => 0,
                    'IsApp' => 'N',
                    'IsCC' => 'N',
                    'isQL' => 0,
                    'isShared' => 0,
                    'ProductID' => 5,
                    'MaintainLeader' => 'N',
                ]);

                // Gọi updatePVToCommission để tính hoa hồng
                $this->commissionService->updatePVToCommission($pvUser);

                return response()->json([
                    'success' => true,
                    'user_id' => $newUser->UserID,
                    'username' => $newUser->UserName,
                    'node_id' => $node?->NodeID,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'user_id' => $newUser->UserID,
            ], 500);
        }
    }

    /**
     * Helper: Tạo user với các field mặc định
     */
    private function createUserWithDefaults(array $data, Request $request): User
    {
        $defaults = [
            'FullName' => $data['UserName'] ?? 'User',
            'Pass' => 'password123',
            'PassMD5' => md5('password123'),
            'Active' => 'N',
            'StatusMember' => '',
            'DateReg' => now(),
            'CreatedAt' => now(),
            'Sys' => 'V',
            'VerifyKYC' => 'N',
            'VerifyKYC2' => 'N',
            'Type' => 0,
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
            'TokenID' => strtoupper(substr(md5(uniqid()), 0, 6)),
            'IP' => $request->ip() ?? '127.0.0.1',
            'Avatar' => 'avatar.png',
            '2FA' => 'N',
            'lockTransfer' => 'N',
            'ActiveMarket' => 'N',
            'UserAmount' => 0,
            'CRWallet' => 0,
            'USDT_WALLET' => 0,
            'RWallet' => 0,
            'ODICAFFEE' => 0,
            'KHUYENMAI' => 0,
            'THANKHOAN' => 0,
            'THANHVIEN' => 0,
            'TIEUDUNG' => 0,
        ];

        return User::create(array_merge($defaults, $data));
        // Tạo node cho user này trong tbl_node
        // Lưu ý: cần truyền đầy đủ thông tin, trong ví dụ này chỉ là tạo đơn giản. 
        // Tuỳ thuộc vào logic bạn có thể muốn truyền các tham số khác.

        // Lấy thông tin user vừa tạo
        $user = User::where('UserName', $data['UserName'] ?? null)->orderBy('UserID', 'desc')->first();

        if ($user) {
            $referrerUserId = 1;
            // Gán vị trí node (trái/phải), mặc định 'A' là trái, 'B' là phải
            $position = strtoupper($data['pos'] ?? 'A');
            $nodeData = [
                'UserID' => $user->UserID,
                'DateCreate' => now(),
                'MucID' => 0,
                'LevelID' => 0,
                'TotalNodeLeft' => 0,
                'TotalNodeRight' => 0,
                'NodeIDLeft' => 0,
                'NodeIDRight' => 0,
            ];
            $node = \App\Models\TblNode::create($nodeData);
        }
    }
    public function calculateCommission(int $id): JsonResponse
    {
        // $liststransaction = TblTransaction::all();
        // foreach ($liststransaction as $transaction) {
        //     $this->commissionService->thuongcongsinhIB($transaction->user_id, $transaction->user_id, $transaction->point, $transaction->cnid, 'CH', $transaction->id, 1, $transaction->created_at);
        // }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Tính hoa hồng thành công',
        // ], 200);
        try {
            // Load relationship 'node' vì updatePVToCommission cần dùng $pvUser->node
            $pvUser = TblPvUser::with('node')->find($id);

            if (!$pvUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'TblPvUser không tồn tại với ID: ' . $id,
                ], 404);
            }

            if (!$pvUser->node) {
                return response()->json([
                    'success' => false,
                    'message' => 'TblPvUser ID ' . $id . ' chưa có node',
                ], 400);
            }

            // Gọi updatePVToCommission
            $this->commissionService->updatePVToCommission($pvUser);

            return response()->json([
                'success' => true,
                'message' => 'Tính hoa hồng thành công',
                'pv_user_id' => $pvUser->ID,
                'node_id' => $pvUser->node->NodeID,
                'user_id' => $pvUser->node->UserID,
                'pv' => $pvUser->PV,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}
