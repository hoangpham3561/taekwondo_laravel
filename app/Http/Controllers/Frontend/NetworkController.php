<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\user\NetworkController as ApiNetworkController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use Illuminate\Http\Request;
use App\Models\TblNode;

class NetworkController extends BaseController
{
    protected $apiNetworkController;
    protected $apiAuthController;

    public function __construct(
        ApiNetworkController $apiNetworkController,
        ApiAuthController $apiAuthController
    ) {
        parent::__construct();
        $this->apiNetworkController = $apiNetworkController;
        $this->apiAuthController = $apiAuthController;
    }

    public function index(Request $request)
    {
        // Gọi API me() để check đăng nhập và lấy user
        $userResponse = $this->apiAuthController->me($request);
        $userData = json_decode($userResponse->getContent(), true);

        if (!$userData['success'] || $userResponse->getStatusCode() === 401) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để xem mạng lưới.');
        }

        $user = (object) ($userData['data'] ?? []);

        // Kiểm tra node và MucID >= 1
        $userNode = TblNode::where('UserID', $user->UserID)->first();
        if (!$userNode || $userNode->MucID < 1) {
            return redirect()->route($this->userPrefix . '.dashboard')
                ->with('error', 'Bạn không có quyền truy cập trang này. Vui lòng liên hệ admin để được nâng cấp.');
        }

        // Mặc định filter F1 (IndirectID = 1) nếu không có parameter
        $indirectId = $request->input('indirect_id', '1');

        $apiRequest = new Request([
            'indirect_id' => $indirectId,
        ]);

        // Gọi API để lấy thông tin mạng lưới
        $networkResponse = $this->apiNetworkController->index($apiRequest);
        $networkData = json_decode($networkResponse->getContent(), true);

        if (!$networkData['success']) {
            return redirect()->route($this->userPrefix . '.dashboard')
                ->with('error', 'Không thể tải thông tin mạng lưới.');
        }

        $data = $networkData['data'] ?? [];

        // Xử lý members data - map field từ API (snake_case) sang format view cần (PascalCase)
        $members = collect($data['members'] ?? [])->map(function ($member) {
            return (object) [
                'UserID' => $member['id'] ?? null,
                'UserName' => $member['username'] ?? 'N/A',
                'FullName' => $member['full_name'] ?? 'N/A',
                'Email' => $member['email'] ?? null,
                'TotalDeposit' => $member['total_deposit'] ?? 0,
                'DateReg' => $member['date_reg'] ?? null,
                'DateCreate' => $member['date_create'] ?? null,
                'IndirectID' => $member['indirect_id'] ?? null,
            ];
        });

        $userNode = TblNode::where('UserID', $user->UserID)->first();
        $paPersonal = $userNode ? ($userNode->TotalPV ?? 0) : 0;
        $paSystem = $userNode ? ($userNode->PVSystem ?? 0) : 0;

        return view('page.frontend.network.network', [
            'userPrefix' => $this->userPrefix,
            'user' => $user,
            'referralLink' => $data['referral_link'] ?? '#',
            'totalMembers' => $data['total_members'] ?? 0,
            'totalDeposit' => $data['total_deposit'] ?? 0,
            'members' => $members,
            'paPersonal' => $paPersonal,
            'paSystem' => $paSystem,
        ]);
    }

    public function tree()
    {
        $userResponse = $this->apiAuthController->me(request());
        $userData = json_decode($userResponse->getContent(), true);

        if (!$userData['success'] || $userResponse->getStatusCode() === 401) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để xem cây thư mục.');
        }

        $user = (object) ($userData['data'] ?? []);

        // Gọi API để lấy tree data
        $apiRequest = new Request();
        $treeResponse = $this->apiNetworkController->tree($apiRequest);
        $treeData = json_decode($treeResponse->getContent(), true);

        if (!$treeData['success']) {
            return redirect()->route($this->userPrefix . '.dashboard')
                ->with('error', $treeData['message'] ?? 'Không thể tải cây thư mục.');
        }

        // Render HTML trực tiếp từ API data (snake_case)
        $treeHtml = $this->renderTreeHtml($treeData['data'], 0);

        return view('page.frontend.network.network-tree', [
            'userPrefix' => $this->userPrefix,
            'user' => $user,
            'treeHtml' => $treeHtml,
        ]);
    }

    /**
     * Render HTML cho cây thư mục
     * Sử dụng trực tiếp data từ API (snake_case)
     */
    private function renderTreeHtml($treeData, $level = 0)
    {
        if (!$treeData) {
            return '';
        }

        $html = '<li>';
        $html .= '<div class="network-tree-node">';

        if (!empty($treeData['children'])) {
            $toggleIcon = $level == 0 ? '-' : '+';
            $html .= '<span class="network-tree-toggle">' . $toggleIcon . '</span>';
        } else {
            $html .= '<span class="network-tree-toggle" style="visibility: hidden;">+</span>';
        }

        $html .= '<div class="network-tree-node-content">';

        if ($level > 0 && !empty($treeData['position'])) {
            $html .= '<span class="text-muted" style="font-size: 12px;">' . htmlspecialchars($treeData['position']) . ' - </span>';
        }

        // Dùng snake_case từ API
        $html .= '<span class="network-tree-node-name">' . ($treeData['username'] ?? '') . ' - ' . ($treeData['full_name'] ?? '') . '</span>';

        $html .= '<span class="network-tree-values">';
        $html .= '<span>Đơn hàng: ' . htmlspecialchars($treeData['muc_name'] ?? '') . '</span>';
        $html .= '<span>Danh hiệu: ' . htmlspecialchars($treeData['level_name'] ?? '') . '</span>';
        $html .= '<span>Doanh số cá nhân: ' . number_format($treeData['total_pv'] ?? 0, 0, ',', '.') . '</span>';
        $html .= '<span>Doanh số hệ thống: ' . number_format($treeData['pv_system'] ?? 0, 0, ',', '.') . '</span>';
        $html .= '<span>Số lượng: ' . number_format($treeData['downline_count'] ?? 0, 0, ',', '.') . '</span>';
        $html .= '</span>';

        $html .= '</div>';
        $html .= '</div>';

        if (!empty($treeData['children'])) {
            $expanded = $level == 0 ? 'expanded' : '';
            $html .= '<ul class="network-tree-children ' . $expanded . '">';
            foreach ($treeData['children'] as $child) {
                $html .= $this->renderTreeHtml($child, $level + 1);
            }
            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }
}
