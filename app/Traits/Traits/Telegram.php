<?php

namespace App\Traits;


trait Telegram
{
    /**
     * Gửi message Telegram đến group/channel cụ thể
     * 
     * @param string $message Nội dung message
     * @param string|null $group Tên group (user_management, withdraw, transfer, etc.) hoặc null để dùng default
     * @return mixed
     */
    public function sendMesssageTelegram($message, $group = null)
    {
        $token = config('services.telegram.token');
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

        try {
            $chatId = $this->getTelegramChatId($group);
            $post = [
                'chat_id' => $chatId,
                'text'    => $message,
                'parse_mode' => "HTML",
            ];

            $client = new \GuzzleHttp\Client();

            $request = $client->post($url, [
                'headers' => ['Accept' => '*/*'],
                'form_params' => $post
            ]);

            return $request->getStatusCode();
        } catch (\Exception $ex) {
            \Log::error('Telegram error: ' . $ex->getMessage(), [
                'group' => $group,
                'exception' => $ex
            ]);
            return false;
        }
    }

    /**
     * Lấy chat_id từ group name hoặc dùng default
     * 
     * @param string|null $group
     * @return string|null
     */
    private function getTelegramChatId($group = null)
    {
        $groups = config('services.telegram.groups', []);
        
        if (empty($group)) {
            return $groups['default'] ?? config('services.telegram.channel_id');
        }

        if (isset($groups[$group])) {
            return $groups[$group];
        }
            
        return $groups['default'] ?? config('services.telegram.channel_id');
    }

    /**
     * Gửi message với inline keyboard buttons
     * 
     * @param string $message Nội dung message
     * @param array $inlineKeyboard Mảng các buttons [['text' => 'Button', 'callback_data' => 'data']]
     * @param string|null $group Tên group
     * @return mixed
     */
    public function sendMessageWithKeyboard($message, $inlineKeyboard = [], $group = null)
    {
        $token = config('services.telegram.token');
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

        try {
            $chatId = $this->getTelegramChatId($group);

            $post = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => "HTML",
            ];

            if (!empty($inlineKeyboard)) {
                $post['reply_markup'] = json_encode([
                    'inline_keyboard' => [$inlineKeyboard]
                ]);
            }

            $client = new \GuzzleHttp\Client();
            $request = $client->post($url, [
                'headers' => ['Accept' => '*/*'],
                'form_params' => $post
            ]);

            return $request->getStatusCode();
        } catch (\Exception $ex) {
            \Log::error('Telegram error: ' . $ex->getMessage(), [
                'group' => $group,
                'exception' => $ex
            ]);
            return false;
        }
    }

    public function adminLoginTelegram($username, $google2fa, $dateTime, $ip, $status)
    {
        $telegramToken = config('services.telegram.token');
        if (!empty($telegramToken)) {
            try {
                $xhtml = "Login admin:  <b>$username</b>\n"
                    . "Code 2fa: $google2fa\n"
                    . "Time: $dateTime\n"
                    . "IP: <b>$ip</b>\n"
                    . "Status: <b>$status</b>\n";
                return $this->sendMesssageTelegram($xhtml, 'admin');
            } catch (\Exception $ex) {
                \Log::error('Admin login Telegram error: ' . $ex->getMessage());
                return false;
            }
        }
    }
}
