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
        $rows = [
            [
                'name' => 'Su kien',
                'slug' => 'su-kien',
                'parent_id' => 0,
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Thanh tich',
                'slug' => 'thanh-tich',
                'parent_id' => 0,
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Tin tuc',
                'slug' => 'tin-tuc',
                'parent_id' => 0,
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Thong bao',
                'slug' => 'thong-bao',
                'parent_id' => 0,
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ];

        DB::table('category')->upsert(
            $rows,
            ['slug'],
            ['name', 'parent_id', 'status', 'updated_at']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('category')) {
            return;
        }

        DB::table('category')
            ->where('parent_id', 0)
            ->whereIn('slug', ['su-kien', 'thanh-tich', 'tin-tuc', 'thong-bao'])
            ->delete();
    }
};
