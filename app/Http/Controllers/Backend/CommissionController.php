<?php

namespace App\Http\Controllers\Backend;


use App\Models\TblTransaction;
use Illuminate\Http\Request;

class CommissionController extends BaseController
{
    private $pathViewController = 'pages.backend.commission.';

    /**
     * Display a listing of commissions
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = TblTransaction::with(['user', 'user2'])
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo UserName/Email/FullName của người nhận hoa hồng (user_id)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('UserName', 'like', '%' . $search . '%')
                    ->orWhere('Email', 'like', '%' . $search . '%')
                    ->orWhere('FullName', 'like', '%' . $search . '%');
            });
        }

        // Tìm kiếm theo UserName/Email/FullName của người tạo hoa hồng (user_id2)
        if ($request->has('from_user_name') && !empty($request->from_user_name)) {
            $fromUserName = $request->from_user_name;
            $query->whereHas('user2', function ($q) use ($fromUserName) {
                $q->where('UserName', 'like', '%' . $fromUserName . '%')
                    ->orWhere('Email', 'like', '%' . $fromUserName . '%')
                    ->orWhere('FullName', 'like', '%' . $fromUserName . '%');
            });
        }

        // Lọc theo loại hoa hồng
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Lọc theo currency
        if ($request->has('currency') && !empty($request->currency)) {
            $query->where('currency', $request->currency);
        }

        // Lọc theo khoảng ngày
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Phân trang
        $commissions = $query->paginate(50)->withQueryString();

        // Thống kê tổng hợp (chỉ tính các giao dịch đã duyệt)
        $totalCommission = TblTransaction::where('status', 'Y')
            ->selectRaw('SUM(point + point2) as total')
            ->first();

        $typeNames = [
            'SL' => 'Mua sỉ bán lẻ',
            'DL' => 'Kết Nối đại lý',
            'VIP' => 'Thưởng VIP',
            'CH' => 'Cộng hưởng cộng sinh',
            'TL' => 'Thành lập cửa hàng',
            'TT' => 'Thưởng Tagger',
            'LD' => 'Thưởng lãnh đạo'
        ];


        return view($this->pathViewController . 'index', [
            'commissions' => $commissions,
            'totalCommission' => $totalCommission->total ?? 0,
            'search' => $request->search ?? '',
            'fromUserName' => $request->from_user_name ?? '',
            'type' => $request->type ?? '',
            'currency' => $request->currency ?? '',
            'fromDate' => $request->from_date ?? '',
            'toDate' => $request->to_date ?? '',
            'typeNames' => $typeNames,
        ]);
    }
}
