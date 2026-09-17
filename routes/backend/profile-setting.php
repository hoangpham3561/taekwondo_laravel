<?php

use App\Http\Controllers\Backend\ProfileSettingController;
use App\Helpers\BaseHelper;

$adminPrefix = BaseHelper::getAdminPrefix();

Route::group(['prefix' => $adminPrefix], function () use ($adminPrefix) {
    Route::middleware(['isAdmin'])->group(function () use ($adminPrefix) {
        Route::get('profile-setting', [ProfileSettingController::class, 'index'])->name($adminPrefix . '.profile-setting.index');
        Route::put('profile-setting', [ProfileSettingController::class, 'updateProfile'])->name($adminPrefix . '.profile-setting.update');
        Route::post('profile-setting/change-password', [ProfileSettingController::class, 'changePassword'])->name($adminPrefix . '.profile-setting.change-password');
    });
});

