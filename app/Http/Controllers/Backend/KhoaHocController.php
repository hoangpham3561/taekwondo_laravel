<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\HuanLuyenVien;
use App\Models\CauLacBo;
use App\Models\Branch;
use Illuminate\Http\Request;

class KhoaHocController extends BaseController
{
    protected $pathViewController = 'pages.backend.khoa-hoc.';
    protected $khoaHocPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->khoaHocPrefix = config('core.routes.khoa_hoc.prefix');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KhoaHoc::with(['coach', 'club', 'branch'])
                       ->orderBy('start_date', 'desc');

        // Search & Filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('level', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $khoaHoc = $query->paginate(10);

        return view($this->pathViewController . 'index', compact('khoaHoc'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coaches = HuanLuyenVien::pluck('ho_va_ten', 'id');
        $clubs = CauLacBo::pluck('name', 'id');
        $branches = Branch::pluck('name', 'id');

        return view($this->pathViewController . 'create', compact('coaches', 'clubs', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'level' => 'required|in:beginner,intermediate,advanced',
            'coach_id' => 'required|exists:huan_luyen_vien,id',
            'branch_id' => 'required|exists:chi_nhanh,id',
            'description' => 'nullable|string',
            'quarter' => 'nullable|in:Q1,Q2,Q3,Q4',
            'year' => 'nullable|integer|min:2000|max:2100',
            'club_id' => 'nullable|exists:cau_lac_bo,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'current_students' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        // Set default values for fields that cannot be null
        $validated['current_students'] = $validated['current_students'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;

        KhoaHoc::create($validated);

        return redirect()->route($this->adminPrefix . '.' . $this->khoaHocPrefix . '.index')
                        ->with('success', 'Khóa học đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(KhoaHoc $khoaHoc)
    {
        $khoaHoc->load(['coach', 'club', 'branch']);

        return view($this->pathViewController . 'show', compact('khoaHoc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KhoaHoc $khoaHoc)
    {
        $coaches = HuanLuyenVien::pluck('ho_va_ten', 'id');
        $clubs = CauLacBo::pluck('name', 'id');
        $branches = Branch::pluck('name', 'id');

        return view($this->pathViewController . 'edit', compact('khoaHoc', 'coaches', 'clubs', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KhoaHoc $khoaHoc)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'level' => 'required|in:beginner,intermediate,advanced',
            'coach_id' => 'required|exists:huan_luyen_vien,id',
            'branch_id' => 'required|exists:chi_nhanh,id',
            'description' => 'nullable|string',
            'quarter' => 'nullable|in:Q1,Q2,Q3,Q4',
            'year' => 'nullable|integer|min:2000|max:2100',
            'club_id' => 'nullable|exists:cau_lac_bo,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'current_students' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        // Set default values for fields that cannot be null
        $validated['current_students'] = $validated['current_students'] ?? $khoaHoc->current_students ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? $khoaHoc->is_active ?? true;

        $khoaHoc->update($validated);

        return redirect()->route($this->adminPrefix . '.' . $this->khoaHocPrefix . '.index')
                        ->with('success', 'Khóa học đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KhoaHoc $khoaHoc)
    {
        $khoaHoc->delete();

        return redirect()->route($this->adminPrefix . '.' . $this->khoaHocPrefix . '.index')
                        ->with('success', 'Khóa học đã được xóa!');
    }
}
