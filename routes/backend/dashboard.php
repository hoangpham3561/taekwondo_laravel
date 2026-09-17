<?php

use App\Http\Controllers\Backend\AuthController as BackendAuthController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\NewsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Helpers\BaseHelper;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$adminPrefix = BaseHelper::getAdminPrefix();

Route::group(['prefix' => $adminPrefix], function () use ($adminPrefix) {

    Route::middleware(['isAdmin'])->group(function () use ($adminPrefix) {
        Route::get('/messages', [DashboardController::class, 'messages'])->name($adminPrefix . '.messages.index');

        Route::get('/notifications', [NotificationController::class, 'index'])->name($adminPrefix . '.notifications.index');
        Route::get('/notifications/{id}/open', [NotificationController::class, 'open'])
            ->name($adminPrefix . '.notifications.open');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
            ->name($adminPrefix . '.notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name($adminPrefix . '.notifications.read_all');

        Route::middleware(['role:super_admin'])->group(function () use ($adminPrefix) {
            /** DASHBOARD **/
            $dashboardPrefix = Config::get('core.routes.dashboard.prefix');
            Route::group(['prefix' => $dashboardPrefix], function () use ($adminPrefix) {
                Route::get('/', [DashboardController::class, 'index'])->name($adminPrefix . '.dashboard');
            });
        });
    });
});
