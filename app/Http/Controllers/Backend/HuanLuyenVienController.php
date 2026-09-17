<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\BaseHelper;
use App\Models\HuanLuyenVien;
use App\Models\CapDai;

class HuanLuyenVienController extends BaseController
{
    private $pathViewController = 'pages.backend.huan-luyen-vien.';
    protected $huanLuyenVienPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->huanLuyenVienPrefix = config('core.routes.huan_luyen_vien.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        try {
            $huanLuyenVien = DB::table('huan_luyen_vien')
                ->leftJoin('cap_dai', 'huan_luyen_vien.cap_dai_id', '=', 'cap_dai.id')
                ->select(
                    'huan_luyen_vien.*',
                    'cap_dai.name as cap_dai_name'
                )
                ->orderBy('huan_luyen_vien.id', 'desc')
                ->get()
                ->map(function ($item) {
                    // Convert to object with relationship
                    $item->capDai = (object) ['name' => $item->cap_dai_name];
                    return $item;
                });

            return view($this->pathViewController . 'index', [
                'huanLuyenVien' => $huanLuyenVien,
            ]);
        } catch (\Exception $e) {
            return view($this->pathViewController . 'index', [
                'huanLuyenVien' => collect([]),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view($this->pathViewController . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_don_vi' => 'required|string|max:20',
        ], [
            'ma_don_vi.required' => 'Mã đơn vị là bắt buộc',
            'ma_don_vi.max' => 'Mã đơn vị không được vượt quá 20 ký tự',
        ]);
        
        // TODO: Thêm logic lưu dữ liệu
        // Ví dụ: \App\Models\HuanLuyenVien::create($request->all());

        return redirect()->route($this->adminPrefix . "." . $this->huanLuyenVienPrefix . ".index")
            ->with('success', 'Thêm mới thành công');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $data = DB::table('huan_luyen_vien')
            ->leftJoin('cap_dai', 'huan_luyen_vien.cap_dai_id', '=', 'cap_dai.id')
            ->select(
                'huan_luyen_vien.*',
                'cap_dai.name as cap_dai_name'
            )
            ->where('huan_luyen_vien.id', $id)
            ->first();

        if (!$data) {
            abort(404, 'Huấn luyện viên không tồn tại');
        }

        $capDai = CapDai::orderBy('order_sequence', 'asc')->get();

        return view($this->pathViewController . 'edit', [
            'data' => $data,
            'capDai' => $capDai,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ma_don_vi' => 'required|string|max:20',
        ], [
            'ma_don_vi.required' => 'Mã đơn vị là bắt buộc',
            'ma_don_vi.max' => 'Mã đơn vị không được vượt quá 20 ký tự',
        ]);
        
        // TODO: Thêm logic cập nhật
        // Ví dụ: $huanLuyenVien = \App\Models\HuanLuyenVien::findOrFail($id);
        // $huanLuyenVien->update($request->all());

        return redirect()->route($this->adminPrefix . "." . $this->huanLuyenVienPrefix . ".index")
            ->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // TODO: Thêm logic xóa
        // Ví dụ: \App\Models\HuanLuyenVien::findOrFail($id)->delete();

        return redirect()->route($this->adminPrefix . "." . $this->huanLuyenVienPrefix . ".index")
            ->with('success', 'Xóa thành công');
    }
}
