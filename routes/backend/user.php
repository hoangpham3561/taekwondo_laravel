<?php

use App\Http\Controllers\Backend\AuthController as BackendAuthController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Helpers\BaseHelper;

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

$baseHelper = new BaseHelper();
$adminPrefix = BaseHelper::getAdminPrefix();

Route::group(['prefix' => $adminPrefix], function () use ($adminPrefix, $baseHelper) {

    Route::middleware(['isAdmin'])->group(function () use ($adminPrefix, $baseHelper) {

        Route::middleware(['role:super_admin'])->group(function () use ($adminPrefix, $baseHelper) {
            /** USER **/
            $userPrefix = Config::get('core.routes.user.prefix');
            Route::resource($userPrefix, UserController::class, $baseHelper->customRouteName($adminPrefix, $userPrefix))
                ->except(['show', 'create']);
            Route::get($userPrefix . '/inactive', [UserController::class, 'inactiveIndex'])->name($adminPrefix . '.' . $userPrefix . '.inactive');
            Route::post($userPrefix . '/send-kyc/{id}', [UserController::class, 'sendKyc'])->name($adminPrefix . '.' . $userPrefix . '.send_kyc');
            Route::get($userPrefix . '/kyc', [UserController::class, 'kycList'])->name($adminPrefix . '.' . $userPrefix . '.kycList');
            Route::get($userPrefix . '/kyc/{id}', [UserController::class, 'kycDetail'])->name($adminPrefix . '.' . $userPrefix . '.kycDetail');
            Route::post($userPrefix . '/kyc/{id}', [UserController::class, 'saveKycDetail'])->name($adminPrefix . '.' . $userPrefix . '.saveKycDetail');
        });
    });
});
Route::middleware(['role:super_admin'])->group(function () use ($adminPrefix, $baseHelper) {
    /** USER **/
    $userPrefix = Config::get('core.routes.user.prefix');
    Route::resource($userPrefix, UserController::class, $baseHelper->customRouteName($adminPrefix, $userPrefix))
        ->except(['show']); // Xóa 'create' khỏi except
    Route::get($userPrefix . '/inactive', [UserController::class, 'inactiveIndex'])->name($adminPrefix . '.' . $userPrefix . '.inactive');
    Route::get($userPrefix . '/import', [UserController::class, 'importForm'])->name($adminPrefix . '.' . $userPrefix . '.import');
    Route::post($userPrefix . '/import', [UserController::class, 'import'])->name($adminPrefix . '.' . $userPrefix . '.import.store');
    Route::get($userPrefix . '/import/template', [UserController::class, 'downloadTemplate'])->name($adminPrefix . '.' . $userPrefix . '.import.template');
    // ... existing code ...
});