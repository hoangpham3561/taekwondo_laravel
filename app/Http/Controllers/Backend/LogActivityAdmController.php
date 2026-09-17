<?php

namespace App\Http\Controllers\Backend;

use App\Enums\NewsStatusEnum;
use App\Models\Log_Activity_Adm as LogModel;
use App\Traits\LogActivitiesAdm;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogAdmExport;
use App\Imports\LogAdmImport;

class LogActivityAdmController extends BaseController
{
    use LogActivitiesAdm;
    protected $today;
    protected $params;
    protected $logModel;

    private $pathViewController = 'pages.log_activities_adm.';
    protected $log_activities_adm;

    public function __construct(Carbon $today, LogModel $logModel)
    {
        parent::__construct();
        $this->today = $today;
        $this->logModel = $logModel;
        $this->log_activities_adm = config('core.routes.log_activities_adm.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $start_date = !empty($request['start_date']) ? Carbon::parse($request['start_date'])->format('Y-m-d') : Carbon::parse($this->today)->format('Y-m-d');

        // Method getAll() đã tự eager load admin rồi, không cần gọi with() nữa
        $logs = $this->logModel->getAll($request);

        return view($this->pathViewController . 'index', compact('logs', 'start_date'));
    }

    public function export(Request $request)
    {
        $file = $this->today->format('Y/m/d');
        $type = $request->type;
        return Excel::download(new LogAdmExport(), str_replace('/', '', $file) . '-data' . '.' . $type);
    }

    public function import()
    {
        Excel::import(new LogAdmImport(), request()->file('file'));
        return back();
    }
}
