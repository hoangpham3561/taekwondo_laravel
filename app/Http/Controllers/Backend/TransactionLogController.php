<?php

namespace App\Http\Controllers\Backend;

use App\Models\TblTransactionLog;
use Illuminate\Http\Request;

class TransactionLogController extends BaseController
{
    private $pathViewController = 'pages.backend.commission.';

    /**
     * Display a listing of transaction logs
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = TblTransactionLog::with(['user', 'user2'])
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo UserName/Email/FullName của người nhận (user_id)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('UserName', 'like', '%' . $search . '%')
                    ->orWhere('Email', 'like', '%' . $search . '%')
                    ->orWhere('FullName', 'like', '%' . $search . '%');
            });
        }

        // Tìm kiếm theo UserName/Email/FullName của người tạo (user_id2)
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
        if ($request->has('cnid') && !empty($request->cnid)) {
            $query->where('cnid', $request->cnid);
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
        $transactionLogs = $query->paginate(50)->withQueryString();

        // Thống kê tổng hợp
        $totalPoint = TblTransactionLog::selectRaw('SUM(point) as total')
            ->first();

        $typeNames = [
            'SL' => 'Mua sỉ bán lẻ',
            'DL' => 'Kết Nối đại lý',
            'VIP' => 'Đồng chia trọn đời',
            'CH' => 'Cộng hưởng cộng sinh',
            'TL' => 'Thành lập cửa hàng',
            'TT' => 'Thưởng Tagger',
            'LD' => 'Thưởng lãnh đạo'
        ];
        return view($this->pathViewController . 'transactionlog', [
            'transactionLogs' => $transactionLogs,
            'totalPoint' => $totalPoint->total ?? 0,
            'search' => $request->search ?? '',
            'fromUserName' => $request->from_user_name ?? '',
            'type' => $request->type ?? '',
            'cnid' => $request->cnid ?? '',
            'currency' => $request->currency ?? '',
            'fromDate' => $request->from_date ?? '',
            'toDate' => $request->to_date ?? '',
            'typeNames' => $typeNames,
        ]);
    }
}
