<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManualTradeController extends BaseController
{
    public function index()
    {
        return view('page.frontend.manual.index');
    }
}
