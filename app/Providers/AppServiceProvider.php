<?php

namespace App\Providers;

use App\Models\DangKyHoc;
use App\Models\ThanhToan;
use App\Services\AdminNotifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layout.backend.backend', function ($view) {
            $admin = Auth::guard('admin')->user();
            if ($admin) {
                $view->with([
                    'adminNotificationPreview' => $admin->notifications()->latest()->take(6)->get(),
                    'adminUnreadNotificationCount' => $admin->unreadNotifications()->count(),
                ]);
            } else {
                $view->with([
                    'adminNotificationPreview' => collect(),
                    'adminUnreadNotificationCount' => 0,
                ]);
            }
        });

        DangKyHoc::created(function (DangKyHoc $registration) {
            try {
                AdminNotifier::notifyCourseEnrollment($registration);
            } catch (\Throwable $e) {
                Log::warning('Admin course enrollment notification failed: ' . $e->getMessage(), [
                    'dang_ky_hoc_id' => $registration->getKey(),
                ]);
            }
        });

        ThanhToan::created(function (ThanhToan $payment) {
            if ($payment->status !== 'paid') {
                return;
            }
            try {
                AdminNotifier::notifyTuitionPayment($payment);
            } catch (\Throwable $e) {
                Log::warning('Admin tuition notification failed: ' . $e->getMessage(), [
                    'thanh_toan_id' => $payment->getKey(),
                ]);
            }
        });

        ThanhToan::updated(function (ThanhToan $payment) {
            if (! $payment->wasChanged('status') || $payment->status !== 'paid') {
                return;
            }
            try {
                AdminNotifier::notifyTuitionPayment($payment);
            } catch (\Throwable $e) {
                Log::warning('Admin tuition notification failed: ' . $e->getMessage(), [
                    'thanh_toan_id' => $payment->getKey(),
                ]);
            }
        });
    }
}
