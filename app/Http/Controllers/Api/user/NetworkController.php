<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TblNodeDownlineLogF1;
use App\Models\User;
use App\Models\TblNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    /**
     * Lấy thông tin mạng lưới của user hiện tại
     */
    public function index(Request $request)
    {
        // Lấy user từ session (cho frontend) hoặc từ API token
        $user = Auth::guard('web')->user() ?? $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem mạng lưới.',
            ], 401);
        }

        // Tạo referral link với TokenID
        $referralLink = isset($user->TokenID) && $user->TokenID
            ? 'https://antruongtho.com/sign-up/' . $user->TokenID
            : '#';

        $indirectId = $request->input('indirect_id');

        // Đếm tổng thành viên từ bảng tbl_node_downline_log_f1
        $totalMembers = 0;
        $members = collect([]);
        $totalDeposit = 0;

        if (isset($user->UserID) && $user->UserID) {
            // Query base
            $query = TblNodeDownlineLogF1::where('tbl_node_downline_log_f1.UserID', $user->UserID);

            // Filter theo IndirectID nếu có
            if ($indirectId !== null && $indirectId !== '') {
                $query->where('tbl_node_downline_log_f1.IndirectID', (int) $indirectId);
            }

            // Đếm tổng thành viên
            $totalMembers = (clone $query)
                ->distinct('FUserID')
                ->count('FUserID');

            // Lấy danh sách thành viên với thông tin từ bảng user
            $members = (clone $query)
                ->join('user', 'user.UserID', '=', 'tbl_node_downline_log_f1.FUserID')
                ->select(
                    'user.UserID',
                    'user.UserName',
                    'user.Email',
                    'user.FullName',
                    'user.DateReg',
                    'tbl_node_downline_log_f1.DateCreate',
                    'tbl_node_downline_log_f1.IndirectID'
                )
                ->distinct('tbl_node_downline_log_f1.FUserID')
                ->orderBy('tbl_node_downline_log_f1.DateCreate', 'desc')
                ->get()
                ->map(function ($member) {
                    // Tính tổng nạp của từng member
                    $totalDeposit = Order::where('UserID', $member->UserID)
                        ->where('Status', 'Y')
                        ->sum('TotalAmount');

                    return [
                        'id' => $member->UserID,
                        'username' => $member->UserName,
                        'full_name' => $member->FullName,
                        'email' => $member->Email,
                        'date_reg' => $member->DateReg,
                        'date_create' => $member->DateCreate,
                        'total_deposit' => (float) $totalDeposit,
                        'indirect_id' => $member->IndirectID,
                    ];
                });

            // Lấy danh sách FUserID của các thành viên
            $memberUserIDs = $members->pluck('id')->toArray();

            // Tính tổng nạp từ bảng orders với Status = 'Y'
            if (!empty($memberUserIDs)) {
                $totalDeposit = Order::whereIn('UserID', $memberUserIDs)
                    ->where('Status', 'Y')
                    ->sum('TotalAmount');
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'referral_link' => $referralLink,
                'total_members' => $totalMembers,
                'total_deposit' => (float) $totalDeposit,
                'members' => $members,
            ],
        ]);
    }

    /**
     * Lấy cây thư mục network của user hiện tại
     */
    public function tree(Request $request)
    {
        // Lấy user từ session (cho frontend) hoặc từ API token
        $user = Auth::guard('web')->user() ?? $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem cây thư mục.',
            ], 401);
        }

        $userId = $user->UserID ?? 0;

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin user.',
            ], 404);
        }

        // Xây dựng cây thư mục
        $treeData = $this->buildNetworkTree($userId);

        if (!$treeData) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xây dựng cây thư mục.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $treeData,
        ]);
    }

    /**
     * Xây dựng cây thư mục đệ quy từ bảng tbl_node_downline_log_f1
     */
    private function buildNetworkTree($userId)
    {
        $currentUser = User::where('UserID', $userId)->first();

        if (!$currentUser) {
            return null;
        }

        $mucNames = [
            0 => 'Khách hàng',
            1 => 'Chuyên nghiệp',
            2 => 'Gói Đồng',
            3 => 'Gói Bạc',
            4 => 'Gói Vàng',
            5 => 'Gói Kim Cương',
        ];

        $levelNames = [
            0 => 'Không có',
            1 => 'Giám Sát Kinh Doanh',
            2 => 'Quản Lý Kinh Doanh',
            3 => 'Phó Giám Đốc Kinh Doanh',
        ];

        $node = TblNode::where('UserID', $currentUser->UserID)->first();

        if (!$node) {
            return null;
        }

        $mucName = $mucNames[$node->MucID ?? 0] ?? 'Khách hàng';

        if (($node->LevelID ?? 0) == 0 && ($node->MucID ?? 0) >= 1) {
            $levelName = 'Đại lý chuyên nghiệp';
        } else {
            $levelName = $levelNames[$node->LevelID ?? 0] ?? 'Không có';
        }

        // Lấy position từ tbl_node
        $position = $node->Position ?? '';

        $f1Members = TblNodeDownlineLogF1::where('UserID', $currentUser->UserID)
            ->where('IndirectID', 1)
            ->orderBy('DateCreate', 'asc')
            ->get()
            ->toArray();

        $children = [];

        foreach ($f1Members as $member) {
            $childNode = $this->buildNetworkTree($member['FUserID']);
            if ($childNode) {
                $children[] = $childNode;
            }
        }

        // Đếm tổng số user tuyến dưới (bao gồm cả con và cháu)
        $downlineCount = $this->countDownlineUsers($userId);

        return [
            'user_id' => $currentUser->UserID,
            'username' => $currentUser->UserName,
            'full_name' => $currentUser->FullName,
            'total_pv' => $node->TotalPV ?? 0,
            'pv_system' => $node->PVSystem ?? 0,
            'muc_name' => $mucName,
            'level_name' => $levelName,
            'position' => $position,
            'downline_count' => $downlineCount,
            'children' => $children,
        ];
    }

    /**
     * Đếm tổng số user tuyến dưới (tất cả cấp)
     */
    private function countDownlineUsers($userId)
    {
        return TblNodeDownlineLogF1::where('UserID', $userId)
            -> where('IndirectID', 1)
            ->distinct('FUserID')
            ->count('FUserID');
    }
}
