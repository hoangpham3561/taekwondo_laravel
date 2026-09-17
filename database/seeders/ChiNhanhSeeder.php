<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiNhanhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // Ensure parent club exists to satisfy FK chi_nhanh.club_id -> cau_lac_bo.id
        $clubCode = 'DONGPHU';
        $clubId = DB::table('cau_lac_bo')->where('club_code', $clubCode)->value('id');
        if (!$clubId) {
            $clubId = DB::table('cau_lac_bo')->insertGetId([
                'club_code' => $clubCode,
                'name' => 'CLB Taekwondo Đồng Phú',
                'address' => 'Đồng Phú',
                'phone' => '0123456780',
                'email' => 'dongphu@taekwondo.local',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chiNhanhData = [
            ['club_id' => $clubId, 'branch_code' => 'GXTN', 'name' => 'CLB Giáo Xứ Tân Lập', 'address' => 'Giáo Xứ Tân Lập, Đồng Phú', 'phone' => '0123456781', 'email' => 'gxtn@dongphu.com'],
            ['club_id' => $clubId, 'branch_code' => 'THTN', 'name' => 'CLB Tiểu Học Tân Lập', 'address' => 'Trường Tiểu Học Tân Lập, Đồng Phú', 'phone' => '0123456782', 'email' => 'thtn@dongphu.com'],
            ['club_id' => $clubId, 'branch_code' => 'THTT', 'name' => 'CLB Tiểu Học Tân Tiến', 'address' => 'Trường Tiểu Học Tân Tiến, Đồng Phú', 'phone' => '0123456783', 'email' => 'thtt@dongphu.com'],
            ['club_id' => $clubId, 'branch_code' => 'THDP', 'name' => 'CLB Tiểu Học Đồng Phú', 'address' => 'Trường Tiểu Học Đồng Phú', 'phone' => '0123456784', 'email' => 'thdp@dongphu.com'],
            ['club_id' => $clubId, 'branch_code' => 'THTP', 'name' => 'CLB Tiểu Học Tân Phú', 'address' => 'Trường Tiểu Học Tân Phú, Đồng Phú', 'phone' => '0123456785', 'email' => 'thtp@dongphu.com'],
            ['club_id' => $clubId, 'branch_code' => 'THTD', 'name' => 'CLB Tiểu Học Tân Lập B', 'address' => 'Trường Học Tân Lập B, Đồng Phú', 'phone' => '0123456786', 'email' => 'thtd@dongphu.com'],
        ];

        foreach ($chiNhanhData as $data) {
            DB::table('chi_nhanh')->updateOrInsert(
                ['branch_code' => $data['branch_code']],
                array_merge($data, [
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );
        }

        $branchIdsByCode = DB::table('chi_nhanh')
            ->whereIn('branch_code', collect($chiNhanhData)->pluck('branch_code')->all())
            ->pluck('id', 'branch_code')
            ->all();

        // Insert quan_ly_chi_nhanh data
        $managerId = DB::table('huan_luyen_vien')->where('id', 3)->value('id');
        if ($managerId) {
            $quanLyData = [
                ['branch_code' => 'GXTN', 'manager_id' => $managerId, 'role' => 'main_manager'],
                ['branch_code' => 'THTN', 'manager_id' => $managerId, 'role' => 'main_manager'],
                ['branch_code' => 'THTT', 'manager_id' => $managerId, 'role' => 'main_manager'],
            ];

            foreach ($quanLyData as $data) {
                $branchId = $branchIdsByCode[$data['branch_code']] ?? null;
                if (!$branchId) {
                    continue;
                }

                DB::table('quan_ly_chi_nhanh')->updateOrInsert(
                    ['branch_id' => $branchId, 'manager_id' => $data['manager_id']],
                    [
                        'role' => $data['role'],
                        'is_active' => true,
                        'assigned_at' => $now,
                    ]
                );
            }
        }

        // Insert tro_giang_chi_nhanh data
        $assistantId = DB::table('huan_luyen_vien')->where('id', 4)->value('id');
        if ($assistantId) {
            $troGiangData = [
                ['branch_code' => 'GXTN', 'assistant_id' => $assistantId],
                ['branch_code' => 'THTN', 'assistant_id' => $assistantId],
                ['branch_code' => 'THTT', 'assistant_id' => $assistantId],
            ];

            foreach ($troGiangData as $data) {
                $branchId = $branchIdsByCode[$data['branch_code']] ?? null;
                if (!$branchId) {
                    continue;
                }

                DB::table('tro_giang_chi_nhanh')->updateOrInsert(
                    ['branch_id' => $branchId, 'assistant_id' => $data['assistant_id']],
                    [
                        'is_active' => true,
                        'assigned_at' => $now,
                    ]
                );
            }
        }
    }
}

