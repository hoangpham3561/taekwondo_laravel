<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\LogActivitiesAdm;
use App\Helpers\BaseHelper;

class BaseController extends Controller
{
    use LogActivitiesAdm;

    protected $adminPrefix;

    public function __construct()
    {
        $this->adminPrefix = BaseHelper::getAdminPrefix();
    }
}
