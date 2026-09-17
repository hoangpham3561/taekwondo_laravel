<?php

use App\Http\Controllers\Backend\AuthController as BackendAuthController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\NewsController;
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

$adminPrefix = BaseHelper::getAdminPrefix();

Route::group(['prefix' => $adminPrefix], function () use ($adminPrefix) {

    Route::middleware(['isAdmin'])->group(function () use ($adminPrefix) {

        Route::middleware(['role:super_admin'])->group(function () use ($adminPrefix) {
            /** NEWS **/
            $newsPrefix = Config::get('core.routes.news.prefix');
            $baseHelper = new BaseHelper();
            Route::resource($newsPrefix, NewsController::class, $baseHelper->customRouteName($adminPrefix, $newsPrefix))
                ->except(['show']);
        });
    });
});
