<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ManualTradeController;
use App\Http\Controllers\Frontend\NetworkController;
use App\Http\Controllers\Frontend\SettingsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WalletController;
use App\Http\Controllers\Frontend\DepositController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\CommissionController;
use App\Http\Controllers\Frontend\TransferController;
// use App\Http\Requests\Frontend\Auth\ForgotPasswordRequest;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Str;

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

$userPrefix = config('core.user_prefix', '');
Route::group(['prefix' => $userPrefix], function () use ($userPrefix) {
    Route::middleware(['isUser'])->group(function () use ($userPrefix) {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name($userPrefix . '.dashboard');
        Route::post('/dashboard', [DashboardController::class, 'updateProfile'])->name($userPrefix . '.updateProfile');
        Route::post('/dashboard/bank-info', [DashboardController::class, 'updateBankInfo'])->name($userPrefix . '.updateBankInfo');

        // Change Email
        Route::post('/request-change-email', [DashboardController::class, 'requestChangeEmail'])->name($userPrefix . '.requestChangeEmail');

        Route::get('/manual-trade', [ManualTradeController::class, 'index'])->name($userPrefix . '.manual-trade');
        Route::get('/network', [NetworkController::class, 'index'])->name($userPrefix . '.network');
        Route::get('/network-tree', [NetworkController::class, 'tree'])->name($userPrefix . '.network-tree');
        Route::get('/settings', [SettingsController::class, 'index'])->name($userPrefix . '.settings');

        /** RÚT TIỀN **/
        Route::get('/wallet', [WalletController::class, 'index'])->name($userPrefix . '.wallet');
        Route::post('/wallet', [WalletController::class, 'doWithdraw'])->name($userPrefix . '.doWithdraw');
        /** RÚT TIỀN **/

        Route::get('/deposit', [DepositController::class, 'index'])->name($userPrefix . '.deposit');
        Route::post('/deposit', [DepositController::class, 'doDeposit'])->name($userPrefix . '.doDeposit');
        Route::get('/deposit/status/{deposit}', [DepositController::class, 'checkStatus'])->name($userPrefix . '.deposit.status');
        Route::get('/commission', [CommissionController::class, 'index'])->name($userPrefix . '.commission');
        Route::get('/commission/detail', [CommissionController::class, 'detail'])->name($userPrefix . '.commission.detail');
        Route::get('/transfer', [TransferController::class, 'index'])->name($userPrefix . '.transfer');
        Route::post('/transfer', [TransferController::class, 'doTransfer'])->name($userPrefix . '.doTransfer');
        Route::get('/transfer/search-user', [TransferController::class, 'searchUser'])->name($userPrefix . '.transfer.searchUser');

        Route::post('/change-password', [UserController::class, 'changePassword'])->name($userPrefix . '.changePassword');
        Route::get('/change-password', [UserController::class, 'showChangePassword'])->name($userPrefix . '.showChangePassword');


        Route::post('/change-avatar', [UserController::class, 'changeAvatar'])->name($userPrefix . '.changeAvatar');
        Route::post('/enable-2fa', [UserController::class, 'enable2FA'])->name($userPrefix . '.enable2FA');
        Route::post('/forgot-2fa', [UserController::class, 'forgot2FA'])->name($userPrefix . '.forgot2FA');
        Route::post('/kyc-account', [UserController::class, 'kycAccount'])->name($userPrefix . '.kycAccount');
    });

    Route::get('/confirm-change-email/{token}', [DashboardController::class, 'confirmChangeEmail'])
            ->name($userPrefix . '.confirmChangeEmail')
            ->withoutMiddleware(['auth:web', 'check.kyc']);
            
    Route::middleware(['guest:web'])->group(function () use ($userPrefix) {
        Route::get('/sign-in', [FrontendAuthController::class, 'login'])->name($userPrefix . '.login');
        Route::post('/create-session-from-token', [FrontendAuthController::class, 'createSessionFromToken'])->name($userPrefix . '.createSessionFromToken');
        Route::get('/sign-up/{ref_code?}', [FrontendAuthController::class, 'signUp'])->name($userPrefix . '.sign-up');

        // Quên mật khẩu
        Route::get('/forgot-password', [FrontendAuthController::class, 'forgotPassword'])->name($userPrefix . '.forgotPassword');
        Route::post('/forgot-password', [FrontendAuthController::class, 'doForgotPassword'])->name($userPrefix . '.doForgotPassword');

        // KYC mail
        Route::get('/kyc/{token}', [UserController::class, 'kyc'])->name($userPrefix . '.kyc');
    });

    Route::post('/bank-transfer/webhook', [DepositController::class, 'webhook'])
        ->name($userPrefix . '.deposit.webhook');
});
