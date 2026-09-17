<?php

namespace App\Http\Controllers\Backend;

use App\Models\TblNodeDownlineLogF1;
use App\Models\User;
use Illuminate\Http\Request;

class TreeDownController extends BaseController
{
    private $pathViewController = 'pages.backend.tree.';

    /**
     * Display a listing of tree down logs
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = TblNodeDownlineLogF1::with(['user.node', 'fUser'])
            ->orderBy('DateCreate', 'desc');

        // Tìm kiếm theo UserName/Email/FullName của user
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('UserName', '=', $search);
                } else {
                    $q->where('UserName', 'like', '%' . $search . '%')
                        ->orWhere('Email', 'like', '%' . $search . '%');
                }
            });
        }

        // Tìm kiếm theo UserName của FUser
        if ($request->has('f_user_name') && !empty($request->f_user_name)) {
            $fUserName = $request->f_user_name;
            $query->whereHas('fUser', function ($q) use ($fUserName) {
                // Nếu là số thì tìm chính xác UserName, nếu không thì dùng LIKE
                if (is_numeric($fUserName)) {
                    $q->where('UserName', '=', $fUserName);
                } else {
                    $q->where('UserName', 'like', '%' . $fUserName . '%')
                        ->orWhere('Email', 'like', '%' . $fUserName . '%');
                }
            });
        }


        // Lọc theo IndirectID
        if ($request->has('indirect_id') && !empty($request->indirect_id)) {
            $query->where('IndirectID', $request->indirect_id);
        }



        // Phân trang
        $treeDownLogs = $query->paginate(50)->withQueryString();
        // Thống kê tổng hợp
        $totalRecords = TblNodeDownlineLogF1::count();

        return view('pages.backend.tree.view_tree_down', [
            'treeDownLogs' => $treeDownLogs,
            'totalRecords' => $totalRecords,
            'search' => $request->search ?? '',
            'fUserName' => $request->f_user_name ?? '',
            'type' => $request->type ?? '',
            'indirectId' => $request->indirect_id ?? '',
            'fromDate' => $request->from_date ?? '',
            'toDate' => $request->to_date ?? '',
        ]);
    }
}
