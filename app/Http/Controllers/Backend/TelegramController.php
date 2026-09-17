<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Telegram;
use App\Traits\Coinmarketcap;

class TelegramController extends BaseController
{
    use Telegram, Coinmarketcap;

    protected $telegramPrefix;
    private $pathViewController = 'pages.telegram.';

    public function __construct()
    {
        parent::__construct();
        $this->telegramPrefix = "telegram";
    }

    public function form()
    {
        return view($this->pathViewController . 'create');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $text = "A new contact us query test\n"
            . "<b>Email Address: </b>\n"
            . "$request->email\n"
            . "<b>Message: </b>\n"
            . $request->message;
        $this->sendMesssageTelegram($text);

        return redirect()->back();
    }

    public function cmc() {
        dd($this->getCoins());
    }
}
