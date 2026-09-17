<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KycStatusEnum;
use App\Http\Requests\Backend\User\ApproveKycRequest;
use App\Models\VoSinh; // Thêm import
use App\Http\Requests\Backend\User\CreateUserRequest;
use App\Http\Requests\Backend\User\UpdateUserRequest;
use App\Services\VoSinhService;
use App\Imports\VoSinhImport;
use App\Exports\VoSinhTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    private $pathViewController = 'pages.backend.user.';
    protected $voSinhService;
    protected $userPrefix;

    public function __construct(VoSinhService $voSinhService)
    {
        parent::__construct();
        $this->voSinhService = $voSinhService;
        $this->userPrefix = config('core.routes.user.prefix');
    }

    public function index(Request $request)
    {
        $users = $this->voSinhService->getAll($request);
        $groupByStatus = $this->voSinhService->groupByStatus()->toArray();

        // Load danh sách cấp đai cho filter
        $capDai = \App\Models\CapDai::orderBy('order_sequence', 'asc')->get();

        return view($this->pathViewController . 'index', [
            'users' => $users,
            'groupByStatus' => $groupByStatus,
            'capDai' => $capDai,
        ]);
    }

    // ... existing code ...

    public function edit($id)
    {
        $vo_sinhStatus = [
            true => 'Hoạt động',
            false => 'Không hoạt động',
        ];
        $data = VoSinh::with('capDai')->findOrFail($id); // Đổi từ User thành VoSinh
        $capDai = \App\Models\CapDai::ordered()->get();

        return view($this->pathViewController . 'edit', [
            'data' => $data,
            'vo_sinhStatus' => $vo_sinhStatus,
            'capDai' => $capDai,
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $this->voSinhService->update($request, $id);

        return redirect()
            ->route($this->adminPrefix . '.' . $this->userPrefix . '.index')
            ->with('success', 'Cập nhật võ sinh thành công.');
    }

    public function destroy($id) // Đổi từ User $user thành $id
    {
        $voSinh = VoSinh::findOrFail($id);
        $this->voSinhService->delete($voSinh);

        return redirect()
            ->route($this->adminPrefix . '.' . $this->userPrefix . '.index')
            ->with('success', 'Đã xóa võ sinh.');
    }
    public function create()
        {
            $vo_sinhStatus = [
                true => 'Hoạt động',
                false => 'Không hoạt động',
                ];

            // Load danh sách cấp đai nếu cần
            $capDai = \App\Models\CapDai::ordered()->get();

            return view($this->pathViewController . 'create', [
                'vo_sinhStatus' => $vo_sinhStatus,
                'userStatus' => $vo_sinhStatus,
                'capDai' => $capDai,
            ]);
        }

        public function store(CreateUserRequest $request)
        {
            $data = $this->voSinhService->store($request);
            return redirect()->route($this->adminPrefix . '.' . $this->userPrefix . '.index')
                ->with('success', 'Thêm mới võ sinh thành công');
    }

    /**
     * Show import form
     */
    public function importForm()
    {
    // Redirect về trang create, form import sẽ được tích hợp ở đó
    return redirect()->route($this->adminPrefix . '.' . $this->userPrefix . '.create')
        ->with('show_import', true); // Flag để hiển thị form import
    }

    /**
     * Download template Excel file
     */
    public function downloadTemplate()
    {
        return Excel::download(new VoSinhTemplateExport, 'vo_sinh_template.xlsx');
    }

    /**
     * Handle import Excel/CSV file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');

            // Import data
            $import = new VoSinhImport();
            Excel::import($import, $file);

            // Get failures if any
            $failures = $import->failures();
            $failureCount = count($failures);

            if ($failureCount > 0) {
                $message = "Import thành công với một số lỗi. Có {$failureCount} dòng bị lỗi.";
                return redirect()->route($this->adminPrefix . '.' . $this->userPrefix . '.create')
                    ->with('warning', $message)
                    ->with('import_failures', $failures);
            }

            return redirect()->route($this->adminPrefix . '.' . $this->userPrefix . '.index')
                ->with('success', 'Import dữ liệu thành công!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->route($this->adminPrefix . '.' . $this->userPrefix . '.create')
                ->with('error', 'Import thất bại. Vui lòng kiểm tra lại file Excel.')
                ->with('import_failures', $failures);
        } catch (\Exception $e) {
            return redirect()->route($this->adminPrefix . '.' . $this->userPrefix . '.create')
                ->with('error', 'Lỗi khi import: ' . $e->getMessage());
    }
}
    // ... existing code ...
}
