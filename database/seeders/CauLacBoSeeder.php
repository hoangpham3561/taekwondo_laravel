<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CauLacBoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cau_lac_bo')->insert([
            'club_code' => '_00468',
            'name' => 'CLB Đồng Phú',
            'address' => 'Đồng Phú, Bình Phước',
            'phone' => '0123456789',
            'email' => 'dongphu@taekwondo.com',
            'head_coach_id' => 1,
            'description' => 'CLB Taekwondo Đồng Phú - Thầy Tiến HLV trưởng',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

