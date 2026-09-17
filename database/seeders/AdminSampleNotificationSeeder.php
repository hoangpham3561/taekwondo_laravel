<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\AdminDatabaseNotification;
use Illuminate\Database\Seeder;

class AdminSampleNotificationSeeder extends Seeder
{
    /**
     * Tạo một thông báo mẫu cho tài khoản quản trị đầu tiên (bảng huan_luyen_vien / model User).
     * Chạy: php artisan db:seed --class=AdminSampleNotificationSeeder
     */
    public function run(): void
    {
        $admin = User::query()->orderBy('id')->first();
        if (! $admin) {
            return;
        }

        $admin->notify(new AdminDatabaseNotification(
            'Chào mừng đến bảng quản trị',
            'Đây là thông báo mẫu. Bạn có thể đánh dấu đã đọc hoặc mở thông báo từ menu trên cùng.',
            null
        ));
    }
}
