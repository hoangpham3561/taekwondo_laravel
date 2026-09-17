<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\user\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (không cần authentication)
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (cần authentication)
Route::prefix('v1')->middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Cart routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::post('/cart/clear', [CartController::class, 'clear']);
});

// Legacy routes (giữ lại để tương thích)
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Telegram webhook - API routes không có CSRF
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handleCallback']);

// Test routes (chỉ trong local/testing)
if (app()->environment(['local', 'testing'])) {
    // Endpoint duy nhất: Tạo user và lên cây nhị phân
    // Nếu referrer_user_id=0 hoặc không có → tạo root user
    // Nếu có referrer_user_id → tạo user con và lên cây
    //http://127.0.0.1:8000/api/test/node-tree?referrer_user_id=8&username=anhduong&pos=L&nguoimuahang=6&typeOrder=1&pv=1000
    Route::match(['get', 'post'], '/test/node-tree', [\App\Http\Controllers\TestNodeController::class, 'createUserAndPlaceInTree']);
    // Endpoint tính hoa hồng theo ID
    // https://antruongtho.com/api/test/calculate-commission/19
    Route::match(['get', 'post'], '/test/calculate-commission/{id}', [\App\Http\Controllers\TestNodeController::class, 'calculateCommission']);
}
