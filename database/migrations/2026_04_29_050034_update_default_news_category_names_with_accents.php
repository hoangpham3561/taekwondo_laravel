<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('category')) {
            return;
        }

        $now = now();
        $updates = [
            'su-kien' => 'Sự kiện',
            'thanh-tich' => 'Thành tích',
            'tin-tuc' => 'Tin tức',
            'thong-bao' => 'Thông báo',
        ];

        foreach ($updates as $slug => $name) {
            DB::table('category')
                ->where('slug', $slug)
                ->update([
                    'name' => $name,
                    'updated_at' => $now,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('category')) {
            return;
        }

        $now = now();
        $updates = [
            'su-kien' => 'Su kien',
            'thanh-tich' => 'Thanh tich',
            'tin-tuc' => 'Tin tuc',
            'thong-bao' => 'Thong bao',
        ];

        foreach ($updates as $slug => $name) {
            DB::table('category')
                ->where('slug', $slug)
                ->update([
                    'name' => $name,
                    'updated_at' => $now,
                ]);
        }
    }
};
