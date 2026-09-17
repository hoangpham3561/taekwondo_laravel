<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index()
    {
        // Tổng quan dữ liệu
        $totalVoSinh = DB::table('vo_sinh')
            ->where('active_status', true)
            ->count();

        $totalHuanLuyenVien = DB::table('huan_luyen_vien')->count();
        $totalCauLacBo = DB::table('cau_lac_bo')->count();
        $totalKhoaHoc = DB::table('khoa_hoc')->count();

        // Tổng doanh thu đã thanh toán
        $totalRevenue = DB::table('thanh_toan')
            ->where('status', 'paid')
            ->sum('amount');

        // Thống kê võ sinh theo cấp đai
        $thongKeTheoCapDai = DB::table('vo_sinh')
            ->join('cap_dai', 'vo_sinh.cap_dai_id', '=', 'cap_dai.id')
            ->where('vo_sinh.active_status', true)
            ->select('cap_dai.name as cap_dai_name', DB::raw('COUNT(*) as so_luong'))
            ->groupBy('cap_dai.id', 'cap_dai.name')
            ->orderBy('cap_dai.order_sequence', 'asc')
            ->get();

        $latestVoSinh = DB::table('vo_sinh')
            ->select('ho_va_ten', 'ma_hoi_vien', 'created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $latestNews = DB::table('tin_tuc')
            ->select('title', 'published_at', 'created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('pages.backend.dashboard', [
            'totalVoSinh' => $totalVoSinh,
            'totalHuanLuyenVien' => $totalHuanLuyenVien,
            'totalCauLacBo' => $totalCauLacBo,
            'totalKhoaHoc' => $totalKhoaHoc,
            'totalRevenue' => $totalRevenue ?? 0,
            'thongKeTheoCapDai' => $thongKeTheoCapDai,
            'latestVoSinh' => $latestVoSinh,
            'latestNews' => $latestNews,
        ]);
    }

    public function messages()
    {
        return view('pages.backend.messages.index');
    }
}