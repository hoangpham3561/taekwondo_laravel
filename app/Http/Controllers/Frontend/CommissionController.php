<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Api\user\CommissionController as ApiCommissionController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use Illuminate\Http\Request;

class CommissionController extends BaseController
{
    protected $apiCommissionController;
    protected $apiAuthController;

    public function __construct(
        ApiCommissionController $apiCommissionController,
        ApiAuthController $apiAuthController
    ) {
        parent::__construct();
        $this->apiCommissionController = $apiCommissionController;
        $this->apiAuthController = $apiAuthController;
    }

    /**
     * Hiển thị danh sách hoa hồng của user hiện tại
     */
    public function index(Request $request)
    {
        $userResponse = $this->apiAuthController->me($request);
        $userData = json_decode($userResponse->getContent(), true);
        if (!$userData['success'] || $userResponse->getStatusCode() === 401) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để xem hoa hồng.');
        }

        $user = (object) ($userData['data'] ?? []);

        // Gọi API để lấy danh sách hoa hồng
        $apiResponse = $this->apiCommissionController->index($request);
        $apiData = json_decode($apiResponse->getContent(), true);

        if (!$apiData['success']) {
            return redirect()->back()
                ->with('error', $apiData['message'] ?? 'Không thể lấy dữ liệu hoa hồng.');
        }

        // Xử lý data từ API - chuyển array thành collection
        $commissions = collect($apiData['data'] ?? []);

        return view('page.frontend.commission', [
            'commissions' => $commissions,
            'userPrefix' => $this->userPrefix,
            'user' => $user,
        ]);
    }

    /**
     * Hiển thị chi tiết hoa hồng theo loại
     */
    public function detail(Request $request)
    {
        $userResponse = $this->apiAuthController->me($request);
        $userData = json_decode($userResponse->getContent(), true);
        if (!$userData['success'] || $userResponse->getStatusCode() === 401) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để xem hoa hồng.');
        }

        $user = (object) ($userData['data'] ?? []);

        // Validate type
        $type = $request->input('type');
        if (empty($type)) {
            return redirect()->route($this->userPrefix . '.commission')
                ->with('error', 'Loại hoa hồng không hợp lệ.');
        }

        // Map type names
        $typeNames = [
            'SL' => 'Mua sỉ bán lẻ',
            'DL' => 'Kết Nối đại lý',
            'VIP' => 'Thưởng VIP',
            'CH' => 'Cộng hưởng cộng sinh',
            'TL' => 'Thành lập cửa hàng',
            'TT' => 'Thưởng Tagger',
            'LD' => 'Thưởng lãnh đạo'
        ];

        $typeName = $typeNames[$type] ?? 'N/A';

        // Tạo request mới với type filter
        $detailRequest = new Request([
            'type' => $type,
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);

        // Gọi API để lấy chi tiết
        $apiResponse = $this->apiCommissionController->detail($detailRequest);
        $apiData = json_decode($apiResponse->getContent(), true);

        if (!$apiData['success']) {
            return redirect()->route($this->userPrefix . '.commission')
                ->with('error', $apiData['message'] ?? 'Không thể lấy dữ liệu hoa hồng.');
        }

        // Xử lý data từ API - chuyển array thành collection
        $commissions = collect($apiData['data'] ?? []);

        return view('page.frontend.commission-detail', [
            'commissions' => $commissions,
            'type' => $type,
            'typeName' => $typeName,
            'userPrefix' => $this->userPrefix,
            'user' => $user,
        ]);
    }
}
