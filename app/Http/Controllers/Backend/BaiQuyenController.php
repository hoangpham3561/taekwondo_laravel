<?php

namespace App\Http\Controllers\Backend;

use App\Models\BaiQuyen;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BaiQuyenController extends BaseController
{
    private $pathViewController = 'layout.backend.bai-quyen.';
    protected $baiQuyenPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->baiQuyenPrefix = config('core.routes.bai_quyen.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        try {
            $baiQuyen = BaiQuyen::orderBy('id', 'asc')->get();

            return view($this->pathViewController . 'index', [
                'baiQuyen' => $baiQuyen,
            ]);
        } catch (\Exception $e) {
            return view($this->pathViewController . 'index', [
                'baiQuyen' => collect([]),
                'error' => $e->getMessage()
            ]);
        }
    }
}
