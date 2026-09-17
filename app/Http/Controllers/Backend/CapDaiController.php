<?php

namespace App\Http\Controllers\Backend;

use App\Models\CapDai;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CapDaiController extends BaseController
{
    private $pathViewController = 'layout.backend.cap-dai.';
    protected $capDaiPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->capDaiPrefix = config('core.routes.cap_dai.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        try {
            $capDai = CapDai::with('baiQuyen')->orderBy('id', 'asc')->get();

            return view($this->pathViewController . 'index', [
                'capDai' => $capDai,
            ]);
        } catch (\Exception $e) {
            return view($this->pathViewController . 'index', [
                'capDai' => collect([]),
                'error' => $e->getMessage(),
            ]);
        }
    }


}
