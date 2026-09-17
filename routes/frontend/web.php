<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ManualTradeController;
use App\Http\Controllers\Frontend\NetworkController;
use App\Http\Controllers\Frontend\SettingsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;

use App\Http\Controllers\Frontend\NewController;
use App\Http\Controllers\Frontend\ListCoffeeController;
use App\Http\Controllers\Frontend\NewDetailController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AboutUsController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
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

$userPrefix = BaseHelper::getUserPrefix();

Route::group(['prefix' => $userPrefix], function () use ($userPrefix) {
    Route::get('/', [HomeController::class, 'index'])->name($userPrefix . '.index');
    Route::get('/list-coffee', [ListCoffeeController::class, 'index'])->name($userPrefix . '.list-coffee');
    Route::get('/course', [ListCoffeeController::class, 'index'])->name($userPrefix . '.course');
    Route::get('/course-detail/{id}', [ListCoffeeController::class, 'detail'])->name($userPrefix . '.course-detail');
    Route::get('/new', [NewController::class, 'index'])->name($userPrefix . '.new');
    Route::get('/new-detail/{id?}', [NewDetailController::class, 'index'])->name($userPrefix . '.new-detail');
    Route::get('/about-us', [AboutUsController::class, 'index'])->name($userPrefix . '.about-us');
    Route::get('/user', [UserController::class, 'index'])->name($userPrefix . '.user');
    Route::get('/contact', [ContactController::class, 'index'])->name($userPrefix . '.contact');
    Route::post('/contact', [ContactController::class, 'submit'])->name($userPrefix . '.contact.submit');
    Route::get('/cart', [CartController::class, 'index'])->name($userPrefix . '.cart');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name($userPrefix . '.checkout');
    Route::post('/submitcheckout', [CheckoutController::class, 'submitCheckout'])->name($userPrefix . '.submitcheckout');

    Route::post('/cart/add', [CartController::class, 'add'])->name($userPrefix . '.cart.add');
    Route::get('/cart/add-course/{courseId}', [CartController::class, 'addCourse'])->name($userPrefix . '.cart.add-course');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name($userPrefix . '.cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name($userPrefix . '.cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name($userPrefix . '.cart.clear');

    Route::get('/logout', [FrontendAuthController::class, 'logout'])->name($userPrefix . '.logout');
    Route::get('/preview-email', [FrontendAuthController::class, 'previewEmail'])->name($userPrefix . '.preview-email');
});
