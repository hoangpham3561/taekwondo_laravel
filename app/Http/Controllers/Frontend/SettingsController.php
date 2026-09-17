<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;

class SettingsController extends BaseController
{
    public function index()
    {
        $google2fa = new Google2FA();
        $inlineUrl = $google2fa->getQRCodeInline(
            'CRM Laravel',
            Auth::guard('web')->user()->email,
            Auth::guard('web')->user()->secret_2fa
        );
        return view('page.frontend.settings.index', [
            'inlineUrl' => $inlineUrl
        ]);
    }
}
