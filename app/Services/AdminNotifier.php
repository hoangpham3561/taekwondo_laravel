<?php

namespace App\Services;

use App\Helpers\BaseHelper;
use App\Models\DangKyHoc;
use App\Models\Ticket;
use App\Models\ThanhToan;
use App\Models\User;
use App\Notifications\AdminDatabaseNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminNotifier
{
    /**
     * Gửi thông báo nội bộ tới mọi tài khoản quản trị (HLV) đang hoạt động.
     */
    public static function notifyAdmins(string $title, string $body, ?string $actionUrl = null): void
    {
        $admins = User::query()
            ->where('is_active', true)
            ->get();

        if ($admins->isEmpty()) {
            return;
        }

        Notification::send(
            $admins,
            new AdminDatabaseNotification($title, $body, $actionUrl)
        );
    }

    /**
     * Tin liên hệ từ form website (+ email Gmail CLB).
     */
    public static function notifyContactFormSubmission(Ticket $ticket, array $validated): void
    {
        $title = '[Liên hệ website] ' . ($validated['subject'] ?? 'Không tiêu đề');

        $body = sprintf(
            '%s (%s, %s) vừa gửi tin nhắn: %s',
            $validated['name'] ?? '',
            $validated['email'] ?? '',
            $validated['phone'] ?? '',
            Str::limit(strip_tags($validated['message'] ?? ''), 280)
        );

        $adminPrefix = BaseHelper::getAdminPrefix();
        $url = route($adminPrefix . '.tickets.edit', ['ticket' => $ticket->getKey()]);

        self::notifyAdmins($title, $body, $url);
    }

    /**
     * Võ sinh / phụ huynh đăng ký khóa học (bảng dang_ky_hoc).
     */
    public static function notifyCourseEnrollment(DangKyHoc $registration): void
    {
        $registration->loadMissing(['student', 'course']);

        $student = $registration->student;
        $course = $registration->course;

        $studentName = $student?->ho_va_ten ?? 'Võ sinh';
        $courseTitle = $course?->title ?? 'Khóa học';
        $statusLabel = $registration->status === 'pending' ? 'chờ duyệt' : $registration->status;

        $title = 'Đăng ký khóa học mới';
        $body = sprintf(
            '%s vừa đăng ký khóa «%s» (trạng thái: %s).',
            $studentName,
            $courseTitle,
            $statusLabel
        );

        $adminPrefix = BaseHelper::getAdminPrefix();
        $url = $course
            ? route($adminPrefix . '.khoa-hoc.show', ['khoa_hoc' => $course->getKey()])
            : route($adminPrefix . '.khoa-hoc.index');

        self::notifyAdmins($title, $body, $url);
    }

    /**
     * Ghi nhận thanh toán học phí (bảng thanh_toan, trạng thái paid).
     */
    public static function notifyTuitionPayment(ThanhToan $payment): void
    {
        if ($payment->status !== 'paid') {
            return;
        }

        $payment->loadMissing('student');
        $student = $payment->student;
        $studentName = $student?->ho_va_ten ?? 'Võ sinh';

        $amountStr = number_format((float) $payment->amount, 0, ',', '.') . ' đ';
        $period = '';
        if ($payment->month && $payment->year) {
            $period = sprintf(' — kỳ %02d/%d', (int) $payment->month, (int) $payment->year);
        }

        $title = 'Thanh toán học phí';
        $body = sprintf(
            '%s đã thanh toán %s%s.',
            $studentName,
            $amountStr,
            $period
        );

        if ($payment->note) {
            $body .= ' Ghi chú: ' . Str::limit(strip_tags($payment->note), 120);
        }

        $adminPrefix = BaseHelper::getAdminPrefix();
        $url = route($adminPrefix . '.notifications.index');

        self::notifyAdmins($title, $body, $url);
    }
}
