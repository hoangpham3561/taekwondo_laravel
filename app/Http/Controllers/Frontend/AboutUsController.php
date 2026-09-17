<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CauLacBo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutUsController extends BaseController
{
    public function index()
    {
        $club = CauLacBo::query()->latest('id')->first();

        $clubImageUrl = asset('client/images/nodata.png');
        if ($club && !empty($club->logo_url)) {
            if (Str::startsWith($club->logo_url, ['http://', 'https://'])) {
                $clubImageUrl = $club->logo_url;
            } else {
                $clubImageUrl = asset(ltrim($club->logo_url, '/'));
            }
        }

        return view('pages.frontend.about-us', [
            'userPrefix' => $this->userPrefix,
            'club' => $club,
            'clubImageUrl' => $clubImageUrl,
        ]);
    }
}

