<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\BaseHelper;
use App\Models\CauLacBo;
use App\Models\ChiNhanh;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChiNhanhController extends BaseController
{
    protected string $chiNhanhPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->chiNhanhPrefix = BaseHelper::getRoutePrefix('chi_nhanh');
    }

    public function index(Request $request): View
    {
        $chiNhanh = ChiNhanh::query()
            ->with('club')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('pages.backend.chi-nhanh.index', [
            'chiNhanh' => $chiNhanh,
            'adminPrefix' => $this->adminPrefix,
            'chiNhanhPrefix' => $this->chiNhanhPrefix,
        ]);
    }

    public function create(): View
    {
        $clubs = CauLacBo::query()->orderBy('name')->pluck('name', 'id');

        return view('pages.backend.chi-nhanh.create', [
            'clubs' => $clubs,
            'adminPrefix' => $this->adminPrefix,
            'chiNhanhPrefix' => $this->chiNhanhPrefix,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:cau_lac_bo,id',
            'branch_code' => 'required|string|max:20|unique:chi_nhanh,branch_code',
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'nullable|in:0,1',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        ChiNhanh::query()->create($validated);

        return redirect()->route($this->adminPrefix.'.'.$this->chiNhanhPrefix.'.index')
            ->with('success', 'Thêm chi nhánh thành công');
    }

    public function edit(string $id): View
    {
        $chiNhanh = ChiNhanh::query()->findOrFail($id);
        $clubs = CauLacBo::query()->orderBy('name')->pluck('name', 'id');

        return view('pages.backend.chi-nhanh.edit', [
            'chiNhanh' => $chiNhanh,
            'clubs' => $clubs,
            'adminPrefix' => $this->adminPrefix,
            'chiNhanhPrefix' => $this->chiNhanhPrefix,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $chiNhanh = ChiNhanh::query()->findOrFail($id);

        $validated = $request->validate([
            'club_id' => 'required|exists:cau_lac_bo,id',
            'branch_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('chi_nhanh', 'branch_code')->ignore($chiNhanh->id),
            ],
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'nullable|in:0,1',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $chiNhanh->update($validated);

        return redirect()->route($this->adminPrefix.'.'.$this->chiNhanhPrefix.'.index')
            ->with('success', 'Cập nhật chi nhánh thành công');
    }

    public function destroy(string $id): RedirectResponse
    {
        $chiNhanh = ChiNhanh::query()->findOrFail($id);
        $chiNhanh->delete();

        return redirect()->route($this->adminPrefix.'.'.$this->chiNhanhPrefix.'.index')
            ->with('success', 'Đã xóa chi nhánh');
    }
}
