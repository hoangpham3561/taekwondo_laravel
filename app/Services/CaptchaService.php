<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CaptchaService
{
    /**
     * Generate a random numeric CAPTCHA
     * Returns the captcha code
     *
     * @param int $length Number of digits (default: 5)
     * @return string
     */
    public function generate($length = 5): string
    {
        // Generate random number with specified length
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        $code = (string)rand($min, $max);

        // Store answer in session
        Session::put('captcha_answer', $code);
        Session::put('captcha_time', now()->timestamp);

        return $code;
    }

    /**
     * Verify CAPTCHA answer
     *
     * @param string $userAnswer
     * @return bool
     */
    public function verify($userAnswer): bool
    {
        $correctAnswer = Session::get('captcha_answer');
        $captchaTime = Session::get('captcha_time');

        if ($correctAnswer === null || $captchaTime === null) {
            return false;
        }

        // CAPTCHA expires after 10 minutes
        if (now()->timestamp - $captchaTime > 600) {
            Session::forget('captcha_answer');
            Session::forget('captcha_time');
            return false;
        }

        // Clear session after verification
        Session::forget('captcha_answer');
        Session::forget('captcha_time');

        return trim($userAnswer) === trim($correctAnswer);
    }

    /**
     * Get current CAPTCHA code (if exists)
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return Session::get('captcha_answer');
    }
}
