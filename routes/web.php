<?php

use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\Frontend\HomeController;

// Route::get('logs', [LogViewerController::class, 'index']);
Route::get('/', function () {
   return view('welcome');
});

// Smoke test for Vercel / container health checks
Route::get('/health', static function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/index.html', [HomeController::class, 'index']);

Route::get('/test-mail', function () {
   try {
      Mail::raw('Test email từ local với Mailgun thật', function ($message) {
         $message->to('coderit1372000@gmail.com') // Email thật để nhận
            ->subject('Test Mail từ Local');
      });

      return 'Email đã được gửi thành công!';
   } catch (\Exception $e) {
      return 'Lỗi: ' . $e->getMessage();
   }
});

// Load Backend Routes
require __DIR__.'/backend/web.php';
require __DIR__.'/backend/dashboard.php';
require __DIR__.'/backend/user.php';
require __DIR__.'/backend/category.php';
require __DIR__.'/backend/news.php';
require __DIR__.'/backend/ticket.php';
require __DIR__.'/backend/profile-setting.php';

// Load Frontend Routes
require __DIR__.'/frontend/web.php';
require __DIR__.'/frontend/user.php';
