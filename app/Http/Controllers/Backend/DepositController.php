<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

class DepositController extends BaseController
{
    private $pathViewController = 'pages.backend.deposit.';

    /**
     * Display a listing of deposits
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Deposit functionality removed - Order model no longer exists
        return view($this->pathViewController . 'index', [
            'orders' => collect([]),
            'totalDeposit' => 0,
            'totalPending' => 0,
            'search' => $request->search ?? '',
            'orderCode' => $request->order_code ?? '',
            'status' => $request->status ?? '',
            'fromDate' => $request->from_date ?? '',
            'toDate' => $request->to_date ?? '',
        ]);
    }
}
