<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapDaiBaiQuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Link belt levels with required poomsae
        $capDaiBaiQuyenData = [
            // Cấp 8 → 7 (Trắng → Vàng): Thái cực 1
            ['cap_dai_id' => 1, 'bai_quyen_id' => 1, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 7 → 6 (Vàng → Xanh lá): Thái cực 2
            ['cap_dai_id' => 2, 'bai_quyen_id' => 2, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 6 → 5 (Xanh lá → Xanh dương): Thái cực 3
            ['cap_dai_id' => 3, 'bai_quyen_id' => 3, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 5 → 4 (Xanh dương → Đỏ): Thái cực 4
            ['cap_dai_id' => 4, 'bai_quyen_id' => 4, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 4 → 3 (Đỏ cấp thấp): Thái cực 5
            ['cap_dai_id' => 5, 'bai_quyen_id' => 5, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 3 → 2 (Đỏ cấp cao): Thái cực 6
            ['cap_dai_id' => 6, 'bai_quyen_id' => 6, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 2 → 1 (Đỏ cao nhất): Thái cực 7
            ['cap_dai_id' => 7, 'bai_quyen_id' => 7, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // Cấp 1 → 1 Dan (Đỏ → Đen): Thái cực 8
            ['cap_dai_id' => 8, 'bai_quyen_id' => 8, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 1 Dan → 2 Dan: Koryo
            ['cap_dai_id' => 9, 'bai_quyen_id' => 9, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 2 Dan → 3 Dan: Keumgang
            ['cap_dai_id' => 10, 'bai_quyen_id' => 10, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 3 Dan → 4 Dan: Taebaek
            ['cap_dai_id' => 11, 'bai_quyen_id' => 11, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 4 Dan → 5 Dan: Pyongwon
            ['cap_dai_id' => 12, 'bai_quyen_id' => 12, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 5 Dan → 6 Dan: Sipjin
            ['cap_dai_id' => 13, 'bai_quyen_id' => 13, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 6 Dan → 7 Dan: Jitae
            ['cap_dai_id' => 14, 'bai_quyen_id' => 14, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 7 Dan → 8 Dan: Cheonkwon
            ['cap_dai_id' => 15, 'bai_quyen_id' => 15, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 8 Dan → 9 Dan: Hansoo
            ['cap_dai_id' => 16, 'bai_quyen_id' => 16, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
            // 9 Dan → 10 Dan: Ilyeo
            ['cap_dai_id' => 17, 'bai_quyen_id' => 17, 'loai_quyen' => 'bat_buoc', 'thu_tu_uu_tien' => 1],
        ];

        foreach ($capDaiBaiQuyenData as $data) {
            DB::table('cap_dai_bai_quyen')->insert(array_merge($data, [
                'created_at' => now(),
            ]));
        }
    }
}

