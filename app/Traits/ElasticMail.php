<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait ElasticMail
{
    /**
     * Gửi email qua Mailgun (đã chuyển từ ElasticEmail)
     * 
     * @param string $subject
     * @param string $to
     * @param string $view
     * @param array|object $data
     * @param string|null $type Loại email: 'kyc', 'forgot-password', 'forgot-2fa', 'approve-kyc', 'reset-password', etc.
     * @return bool
     */
    public function sendElasticEmail($subject, $to, $view, $data, $type = null)
    {
        try {
            // Nếu không có view, tự động chọn view dựa trên type
            if (empty($view) && !empty($type)) {
                $view = $this->getEmailViewByType($type);
            }

            // Thêm type vào data để view có thể sử dụng
            if (is_array($data)) {
                $data['email_type'] = $type;
            } elseif (is_object($data)) {
                $data->email_type = $type;
            }

            Mail::send($view, ['data' => $data], function ($message) use ($subject, $to, $type) {
                $message->to($to)
                    ->subject($subject);

                // Có thể thêm tag hoặc metadata dựa trên type
                if ($type) {
                    // Mailgun hỗ trợ tags để tracking
                    $message->getHeaders()->addTextHeader('X-Mailgun-Tag', $type);
                }
            });

            return true;
        } catch (\Exception $ex) {
            \Log::error('Mailgun email error: ' . $ex->getMessage(), [
                'to' => $to,
                'subject' => $subject,
                'type' => $type ?? 'unknown',
                'view' => $view,
                'trace' => $ex->getTraceAsString()
            ]);

            throw $ex;
        }
    }

    /**
     * Lấy view email dựa trên type
     * 
     * @param string $type
     * @return string
     */
    protected function getEmailViewByType($type)
    {
        $viewMap = [
            'kyc' => 'emails.send-kyc',
            // 'kyc-image' => 'emails.send-kyc',
            // 'approve-kyc' => 'emails.approve-kyc',
            // 'forgot-2fa' => 'emails.forgot-2fa',
            'forgot-password' => 'emails.forgot-password',
            // 'reply-ticket' => 'emails.reply-ticket',
            'change-email' => 'emails.change-email-confirm',
        ];

        return $viewMap[$type] ?? 'emails.send-kyc';
    }
}
