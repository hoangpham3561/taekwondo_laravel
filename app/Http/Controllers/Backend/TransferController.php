<?php

namespace App\Http\Controllers\Backend;

use App\Models\TransPdRequirement;
use Illuminate\Http\Request;

class TransferController extends BaseController
{
    private $pathViewController = 'pages.backend.transfer.';

    /**
     * Display a listing of internal transfers
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Chỉ lấy các giao dịch chuyển khoản nội bộ (VIP = 'TRANSFER')
        $query = TransPdRequirement::with(['user', 'fUser'])
            ->where('VIP', 'TRANSFER')
            ->orderBy('RequestDate', 'desc');

        // Tìm kiếm theo UserName/Email/FullName của người nhận (UserID)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('UserName', 'like', '%' . $search . '%')
                    ->orWhere('Email', 'like', '%' . $search . '%')
                    ->orWhere('FullName', 'like', '%' . $search . '%');
            });
        }

        // Tìm kiếm theo UserName/Email/FullName của người gửi (FUserID)
        if ($request->has('search_sender') && !empty($request->search_sender)) {
            $searchSender = $request->search_sender;
            $query->whereHas('fUser', function ($q) use ($searchSender) {
                $q->where('UserName', 'like', '%' . $searchSender . '%')
                    ->orWhere('Email', 'like', '%' . $searchSender . '%')
                    ->orWhere('FullName', 'like', '%' . $searchSender . '%');
            });
        }

        // Tìm kiếm theo SerialCode
        if ($request->has('serial_code') && !empty($request->serial_code)) {
            $query->where('SerialCode', 'like', '%' . $request->serial_code . '%');
        }

        // Lọc theo loại ví (Currency)
        if ($request->has('currency') && !empty($request->currency)) {
            $query->where('Currency', $request->currency);
        }

        // Lọc theo khoảng ngày
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('RequestDate', '>=', $request->from_date);
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('RequestDate', '<=', $request->to_date);
        }

        // Phân trang
        $transfers = $query->paginate(50)->withQueryString();

        // Thống kê tổng hợp
        $totalTransfer = TransPdRequirement::where('VIP', 'TRANSFER')
            ->sum('AmountTransfer');

        $totalCount = TransPdRequirement::where('VIP', 'TRANSFER')
            ->count();

        return view($this->pathViewController . 'index', [
            'transfers' => $transfers,
            'totalTransfer' => $totalTransfer ?? 0,
            'totalCount' => $totalCount ?? 0,
            'search' => $request->search ?? '',
            'searchSender' => $request->search_sender ?? '',
            'serialCode' => $request->serial_code ?? '',
            'currency' => $request->currency ?? '',
            'fromDate' => $request->from_date ?? '',
            'toDate' => $request->to_date ?? '',
        ]);
    }
}
