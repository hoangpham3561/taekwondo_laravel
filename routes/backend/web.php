<?php

use App\Http\Controllers\Backend\AuthController as BackendAuthController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\CkEditorController;
use App\Http\Controllers\Backend\TelegramController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\LogActivityAdmController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Helpers\BaseHelper;
use App\Http\Controllers\Backend\CommissionController;
use App\Http\Controllers\Backend\DepositController;
use App\Http\Controllers\Backend\TransferController;
use App\Http\Controllers\Backend\WithdrawController;
use App\Http\Controllers\Backend\TransactionLogController;
use App\Http\Controllers\Backend\TreeDownController;
use App\Http\Controllers\Backend\HuanLuyenVienController;
use App\Http\Controllers\Backend\CapDaiController;
use App\Http\Controllers\Backend\BaiQuyenController;
use App\Http\Controllers\Backend\CauLacBoController;
use App\Http\Controllers\Backend\ChiNhanhController;
use App\Http\Controllers\Backend\KhoaHocController;
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

    // Redirect /admin to /admin/login or /admin/dashboard based on auth status
    Route::get('/', function () {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });


    Route::get('/captcha', [\App\Http\Controllers\Backend\CaptchaController::class, 'generate'])->name($adminPrefix . '.captcha');

    Route::middleware(['isAdmin'])->group(function () use ($adminPrefix, $baseHelper) {
        
        /**
         * SUPER ADMIN - Chỉ super_admin mới vào được
         */
        Route::middleware(['role:super_admin'])->group(function () use ($adminPrefix, $baseHelper) {
            /** LOG ACTIVITIES ADM **/
            $logActiviesAdmPrefix = Config::get('core.routes.log_activities_adm.prefix');
            Route::resource($logActiviesAdmPrefix, LogActivityAdmController::class, $baseHelper->customRouteName($adminPrefix, $logActiviesAdmPrefix))
                ->except(['show']);
            Route::get($logActiviesAdmPrefix . '/export/{type?}', [LogActivityAdmController::class, 'export'])->name($adminPrefix . '.' . $logActiviesAdmPrefix . '.export');
            Route::post($logActiviesAdmPrefix . '/import', [LogActivityAdmController::class, 'import'])->name($adminPrefix . '.' . $logActiviesAdmPrefix . '.import');

            /** CKEDITOR **/
            $ckEditorPrefix = 'ckeditor';
            Route::post($ckEditorPrefix . '/upload', [CkEditorController::class, 'upload'])->name($adminPrefix . '.' . $ckEditorPrefix . '.upload');

            /** TELEGRAM TEST **/
            $telegramPrefix = 'telegram';
            Route::get($telegramPrefix . '/form', [TelegramController::class, 'form'])->name($adminPrefix . '.' . $telegramPrefix . '.form');
            Route::post($telegramPrefix . '/send', [TelegramController::class, 'sendMessage'])->name($adminPrefix . '.' . $telegramPrefix . '.send');
            Route::get($telegramPrefix . '/cmc', [TelegramController::class, 'cmc'])->name($adminPrefix . '.' . $telegramPrefix . '.cmc');

            /** COMMISSIONS **/
            Route::get('/commissions', [CommissionController::class, 'index'])->name($adminPrefix . '.commissions.index');

            /** COMMISSIONS - TRANSACTION LOGS **/
            Route::get('/transaction-logs', [TransactionLogController::class, 'index'])->name($adminPrefix . '.transaction-logs.index');

            /** TREE DOWN LOGS **/
            Route::get('/view-tree-down', [TreeDownController::class, 'index'])->name($adminPrefix . '.view-tree-down.index');

            /** DEPOSITS **/
            Route::get('/deposits', [DepositController::class, 'index'])->name($adminPrefix . '.deposits.index');

            /** TRANSFERS **/
            Route::get('/transfers', [TransferController::class, 'index'])->name($adminPrefix . '.transfers.index');
            $huanLuyenVienPrefix = Config::get('core.routes.huan_luyen_vien.prefix');
            Route::resource($huanLuyenVienPrefix, HuanLuyenVienController::class, $baseHelper->customRouteName($adminPrefix, $huanLuyenVienPrefix))
                ->except(['show']);
            $cauLacBoPrefix = Config::get('core.routes.cau_lac_bo.prefix');
            Route::get($cauLacBoPrefix . '/reference/provinces-open-api-v2', [CauLacBoController::class, 'provincesOpenApiV2Proxy'])
                ->name($adminPrefix . '.' . $cauLacBoPrefix . '.provinces-open-api-v2');
            Route::resource($cauLacBoPrefix, CauLacBoController::class, $baseHelper->customRouteName($adminPrefix, $cauLacBoPrefix))
                ->except(['show']);
            $chiNhanhPrefix = Config::get('core.routes.chi_nhanh.prefix');
            Route::resource($chiNhanhPrefix, ChiNhanhController::class, $baseHelper->customRouteName($adminPrefix, $chiNhanhPrefix))
                ->except(['show']);
            $khoaHocPrefix = Config::get('core.routes.khoa_hoc.prefix');
            Route::resource($khoaHocPrefix, KhoaHocController::class, $baseHelper->customRouteName($adminPrefix, $khoaHocPrefix));
           
            $baiQuyenPrefix = Config::get('core.routes.bai_quyen.prefix');
            Route::get('/' . $baiQuyenPrefix, [BaiQuyenController::class, 'index'])->name($adminPrefix . '.' . $baiQuyenPrefix . '.index');
            $capDaiPrefix = Config::get('core.routes.cap_dai.prefix');
            Route::resource($capDaiPrefix, CapDaiController::class, $baseHelper->customRouteName($adminPrefix, $capDaiPrefix))
                ->except(['show']); 
                
        });

        /**
         * WITHDRAWS - Cả super_admin VÀ admin_ketoan đều vào được
         */
        Route::middleware(['role:super_admin|admin_ketoan'])->group(function () use ($adminPrefix) {
            /** WITHDRAWS **/
            Route::get('/withdraws', [WithdrawController::class, 'index'])->name($adminPrefix . '.withdraws.index');
            Route::post('/withdraws/{id}/approve', [WithdrawController::class, 'approve'])->name($adminPrefix . '.withdraws.approve');
            Route::post('/withdraws/{id}/reject', [WithdrawController::class, 'reject'])->name($adminPrefix . '.withdraws.reject');
            
        });
    });

    Route::middleware(['guest:admin'])->group(function () use ($adminPrefix, $baseHelper) {
        Route::get('/login', [BackendAuthController::class, 'login'])->name($adminPrefix . '.login');
        Route::post('/login', [BackendAuthController::class, 'doLogin'])->name($adminPrefix . '.doLogin');
    });
    Route::get('/logout', [BackendAuthController::class, 'logout'])->name($adminPrefix . '.logout');
});
