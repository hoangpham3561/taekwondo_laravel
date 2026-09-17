<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core tables first (no dependencies)
            CapDaiSeeder::class,
            CategorySeeder::class,
            
            // HLV must be seeded before CLB (because CLB references head_coach_id)
            HuanLuyenVienSeeder::class,
            
            // CLB depends on HLV
            CauLacBoSeeder::class,
            
            // Vo sinh depends on cap_dai
            VoSinhSeeder::class,
            
            // Chi nhanh depends on CLB and HLV
            ChiNhanhSeeder::class,
            
            // Bai quyen tables
            BaiQuyenSeeder::class,
            CapDaiBaiQuyenSeeder::class,
        ]);
    }
}
