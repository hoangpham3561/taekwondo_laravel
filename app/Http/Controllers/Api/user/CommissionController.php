<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\TblTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    /**
     * Lấy danh sách hoa hồng của user hiện tại
     */
    public function index(Request $request)
    {
        // Lấy user từ session (cho frontend) hoặc từ API token
        $user = Auth::guard('web')->user() ?? $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem hoa hồng.',
            ], 401);
        }

        // Base query - chỉ lấy các giao dịch đã duyệt
        $query = TblTransaction::where('user_id', $user->UserID)
            ->where('status', 'Y');

        // Lọc theo ngày bắt đầu
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        // Lọc theo ngày kết thúc
        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Lấy dữ liệu
        $commissions = $query->get();

        // Nhóm theo loại và tính tổng
        $typeNames = [
            'SL' => 'Mua sỉ bán lẻ',
            'DL' => 'Kết Nối đại lý',
            'VIP' => 'Thưởng VIP',
            'CH' => 'Cộng hưởng cộng sinh',
            'TL' => 'Thành lập cửa hàng',
            'TT' => 'Thưởng Tagger',
            'LD' => 'Thưởng lãnh đạo'
        ];

        // Nhóm theo type và tính tổng point
        $groupedCommissions = $commissions->groupBy('type')->map(function ($items, $type) use ($typeNames) {
            $totalPoint = $items->sum(function ($item) {
                return ($item->point ?? 0) + ($item->point2 ?? 0);
            });

            return [
                'type' => $type,
                'type_name' => $typeNames[$type ?? ''] ?? 'N/A',
                'total_point' => (float) $totalPoint,
                'count' => $items->count(),
            ];
        })->values();

        // Sắp xếp theo thứ tự định nghĩa trong typeNames
        $orderedCommissions = collect($typeNames)->map(function ($name, $type) use ($groupedCommissions) {
            $found = $groupedCommissions->firstWhere('type', $type);
            if ($found) {
                return $found;
            }
            return [
                'type' => $type,
                'type_name' => $name,
                'total_point' => 0.0,
                'count' => 0,
            ];
        })->filter(function ($item) {
            // Chỉ hiển thị các loại có dữ liệu hoặc có thể hiển thị tất cả
            return true;
        })->values();

        return response()->json([
            'success' => true,
            'data' => $orderedCommissions,
        ]);
    }

    /**
     * Lấy chi tiết hoa hồng theo loại
     */
    public function detail(Request $request)
    {
        $user = Auth::guard('web')->user() ?? $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem hoa hồng.',
            ], 401);
        }

        $query = TblTransaction::where('user_id', $user->UserID)
            ->where('status', 'Y');

        // Lọc theo loại hoa hồng
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Lọc theo ngày bắt đầu
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        // Lọc theo ngày kết thúc
        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sắp xếp và lấy dữ liệu
        $commissions = $query->orderBy('created_at', 'desc')->get();

        // Transform data
        $commissionsData = $commissions->map(function ($commission) {
            return $this->transformCommission($commission);
        });

        return response()->json([
            'success' => true,
            'data' => $commissionsData,
        ]);
    }

    /**
     * Transform Commission model thành array format
     */
    private function transformCommission($commission)
    {
        $typeNames = [
            'SL' => 'Mua sỉ bán lẻ',
            'DL' => 'Kết Nối đại lý',
            'VIP' => 'Thưởng VIP',
            'CH' => 'Cộng hưởng cộng sinh',
            'TL' => 'Thành lập cửa hàng',
            'TT' => 'Thưởng Tagger',
            'LD' => 'Thưởng lãnh đạo'
        ];

        return [
            'id' => $commission->id,
            'user_id' => $commission->user_id,
            'user_id2' => $commission->user_id2,
            'type' => $commission->type,
            'type_name' => $typeNames[$commission->type ?? ''] ?? 'N/A',
            'point' => (float) ($commission->point ?? 0),
            'point2' => (float) ($commission->point2 ?? 0),
            'total_point' => (float) (($commission->point ?? 0) + ($commission->point2 ?? 0)),
            'pv' => (float) ($commission->pv ?? 0),
            'note' => $commission->note ?? 'N/A',
            'status' => $commission->status ?? 'pending',
            'created_at' => $commission->created_at?->toDateTimeString(),
        ];
    }
}
