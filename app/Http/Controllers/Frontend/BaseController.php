<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\BaseHelper;

class BaseController extends Controller
{
    protected $userPrefix;

    public function __construct()
    {
        $this->userPrefix = BaseHelper::getUserPrefix();
    }
}
