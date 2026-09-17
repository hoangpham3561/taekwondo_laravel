<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sự kiện',
                'slug' => 'su-kien',
                'parent_id' => 0,
                'status' => 'active',
                'description' => 'Danh mục các sự kiện, giải đấu, hoạt động của câu lạc bộ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thành tích',
                'slug' => 'thanh-tich',
                'parent_id' => 0,
                'status' => 'active',
                'description' => 'Danh mục các thành tích, huy chương, giải thưởng của võ sinh và câu lạc bộ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tin tức',
                'slug' => 'tin-tuc',
                'parent_id' => 0,
                'status' => 'active',
                'description' => 'Danh mục tin tức chung của câu lạc bộ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thông báo',
                'slug' => 'thong-bao',
                'parent_id' => 0,
                'status' => 'active',
                'description' => 'Danh mục các thông báo quan trọng từ câu lạc bộ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('category')->insert($categories);
    }
}

