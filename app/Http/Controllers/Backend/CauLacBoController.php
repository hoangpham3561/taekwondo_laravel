<?php

namespace App\Http\Controllers\Backend;

use App\Models\CauLacBo;
use App\Models\HuanLuyenVien;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CauLacBoController extends BaseController
{
    private string $pathViewController = 'pages.backend.caulacbo.';

    protected string $cauLacBoPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->cauLacBoPrefix = config('core.routes.cau_lac_bo.prefix');
    }

    /**
     * Proxy JSON từ provinces.open-api.vn (v2) — cùng origin, tránh CORS / chặn mạng ngoài trên trình duyệt.
     */
    public function provincesOpenApiV2Proxy(Request $request): JsonResponse
    {
        $action = $request->query('action', 'list');
        if ($action === 'list') {
            $url = 'https://provinces.open-api.vn/api/v2/';
        } elseif ($action === 'province') {
            $code = (int) $request->query('code', 0);
            if ($code < 1) {
                return response()->json(['message' => 'Mã tỉnh không hợp lệ'], 422);
            }
            $url = 'https://provinces.open-api.vn/api/v2/p/'.$code.'?depth=2';
        } else {
            return response()->json(['message' => 'action không hợp lệ'], 400);
        }

        $response = Http::timeout(25)->acceptJson()->get($url);
        if (! $response->successful()) {
            return response()->json([
                'message' => 'Không lấy được dữ liệu tỉnh/thành (upstream)',
                'status' => $response->status(),
            ], 502);
        }

        return response()->json($response->json());
    }

    public function index(Request $request): View
    {
        try {
            $cauLacBo = DB::table('cau_lac_bo')
                ->leftJoin('huan_luyen_vien', 'cau_lac_bo.head_coach_id', '=', 'huan_luyen_vien.id')
                ->select(
                    'cau_lac_bo.*',
                    'huan_luyen_vien.ho_va_ten as head_coach_name',
                    'huan_luyen_vien.phone as head_coach_phone',
                    'huan_luyen_vien.email as head_coach_email'
                )
                ->orderBy('cau_lac_bo.id', 'desc')
                ->get();

            return view($this->pathViewController . 'index', [
                'cauLacBo' => $cauLacBo,
            ]);
        } catch (\Exception $e) {
            return view($this->pathViewController . 'index', [
                'cauLacBo' => collect([]),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create(): View
    {
        $coaches = HuanLuyenVien::query()
            ->orderBy('ho_va_ten')
            ->pluck('ho_va_ten', 'id');

        return view($this->pathViewController . 'create', compact('coaches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $provinceCode = $request->filled('province_code') ? (int) $request->input('province_code') : null;
        $wardCode = ($provinceCode && $request->filled('ward_code')) ? (int) $request->input('ward_code') : null;
        $request->merge([
            'province_code' => $provinceCode,
            'ward_code' => $wardCode,
        ]);

        $validated = $request->validate([
            'club_code' => 'required|string|max:20|unique:cau_lac_bo,club_code',
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'legacy_address' => 'nullable|string',
            'province_code' => 'nullable|integer|min:1|required_with:ward_code',
            'ward_code' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'head_coach_id' => 'nullable|exists:huan_luyen_vien,id',
            'description' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'logo_url' => 'nullable|string|max:255',
            'images' => 'nullable|string',
        ]);

        $validated = $this->mergeUploadedLogo($request, $validated);

        CauLacBo::query()->create($validated);

        return redirect()->route($this->adminPrefix . '.' . $this->cauLacBoPrefix . '.index')
            ->with('success', 'Thêm mới thành công');
    }

    public function edit(string $id): View
    {
        $cauLacBo = CauLacBo::query()->findOrFail($id);
        $coaches = HuanLuyenVien::query()
            ->orderBy('ho_va_ten')
            ->pluck('ho_va_ten', 'id');

        return view($this->pathViewController . 'edit', compact('cauLacBo', 'coaches'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $cauLacBo = CauLacBo::query()->findOrFail($id);

        $provinceCode = $request->filled('province_code') ? (int) $request->input('province_code') : null;
        $wardCode = ($provinceCode && $request->filled('ward_code')) ? (int) $request->input('ward_code') : null;
        $request->merge([
            'province_code' => $provinceCode,
            'ward_code' => $wardCode,
        ]);

        $validated = $request->validate([
            'club_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cau_lac_bo', 'club_code')->ignore($cauLacBo->id),
            ],
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'legacy_address' => 'nullable|string',
            'province_code' => 'nullable|integer|min:1|required_with:ward_code',
            'ward_code' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'head_coach_id' => 'nullable|exists:huan_luyen_vien,id',
            'description' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'logo_url' => 'nullable|string|max:255',
            'images' => 'nullable|string',
        ]);

        $validated = $this->mergeUploadedLogo($request, $validated, $cauLacBo->logo_url);
        $cauLacBo->update($validated);

        return redirect()->route($this->adminPrefix . '.' . $this->cauLacBoPrefix . '.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $club = CauLacBo::query()->findOrFail($id);
            $club->delete();
            $message = 'Xóa thành công';
            $type = 'success';
        } catch (\Throwable $e) {
            $message = 'Không thể xóa: câu lạc bộ đang được tham chiếu (chi nhánh, khóa học, …).';
            $type = 'error';
        }

        return redirect()->route($this->adminPrefix . '.' . $this->cauLacBoPrefix . '.index')
            ->with($type, $message);
    }

    /**
     * Nếu có file upload: lưu disk public, ghi đè logo_url bằng URL tuyệt đối; xóa file cũ nếu do hệ thống lưu.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function mergeUploadedLogo(Request $request, array $validated, ?string $previousLogoUrl = null): array
    {
        unset($validated['logo_file']);

        if (! $request->hasFile('logo_file')) {
            return $validated;
        }

        if ($previousLogoUrl) {
            $this->deleteStoredClubLogoIfOwned($previousLogoUrl);
        }

        $path = $request->file('logo_file')->store('cau-lac-bo/logos', 'public');
        $validated['logo_url'] = asset('storage/'.$path);

        return $validated;
    }

    private function deleteStoredClubLogoIfOwned(?string $logoUrl): void
    {
        if ($logoUrl === null || $logoUrl === '') {
            return;
        }
        $path = parse_url($logoUrl, PHP_URL_PATH);
        if (! is_string($path) || ! str_starts_with($path, '/storage/')) {
            return;
        }
        $relative = ltrim(substr($path, strlen('/storage/')), '/');
        if (! str_starts_with($relative, 'cau-lac-bo/logos/')) {
            return;
        }
        Storage::disk('public')->delete($relative);
    }
}
