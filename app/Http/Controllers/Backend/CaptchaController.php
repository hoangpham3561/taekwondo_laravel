<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    protected $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    public function generate()
    {
        // Clean any previous output
        if (ob_get_level()) {
            ob_clean();
        }

        // Generate new CAPTCHA code
        $code = $this->captchaService->generate(5);

        // Create image
        $width = 120;
        $height = 56;
        $image = imagecreatetruecolor($width, $height);

        if (!$image) {
            abort(500, 'Failed to create image');
        }

        // Colors
        $bgColor = imagecolorallocate($image, 250, 250, 250);
        $textColor = imagecolorallocate($image, 60, 60, 60);
        $lineColor = imagecolorallocate($image, 180, 180, 200);

        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add noise lines
        for ($i = 0; $i < 8; $i++) {
            $startX = rand(0, $width);
            $startY = rand(0, $height);
            $endX = rand(0, $width);
            $endY = rand(0, $height);
            imageline($image, $startX, $startY, $endX, $endY, $lineColor);
        }

        // Add text
        $fontSize = 5;
        $x = 10;
        $y = 25;

        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            $offsetX = rand(-1, 2);
            $offsetY = rand(-2, 2);
            $charX = $x + ($i * 22) + $offsetX;
            imagestring($image, $fontSize, $charX, $y + $offsetY, $char, $textColor);
        }

        // Add noise dots
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel($image, rand(0, $width-1), rand(0, $height-1), $lineColor);
        }

        // Output image
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);

        // Return response with proper headers
        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
