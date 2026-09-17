<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapDaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $capDaiData = [
            // Cấp 10 - Cấp 3 (từ bảng 2)
            [
                'name' => 'Cấp 10', 
                'color' => 'White', 
                'order_sequence' => 1, 
                'required_poomsae_code' => 'KT1', 
                'required_poomsae_name' => 'Kĩ Thuật 1 Jang', 
                'description' => 'Đai trắng cấp 10',
                'belt_description' => 'Đai trắng',
                'minimum_time_months' => null,
                'minimum_age' => null,
                'age_requirement_note' => 'Võ sinh mới tham gia tập luyện sẽ được mang đai trắng cấp 10. Tuy nhiên, nếu dưới 6 tuổi thì không được tham dự thi lên cấp. Chỉ có những võ sinh từ 6 tuổi trở lên, tính theo năm tham gia tập luyện, mới được tham gia thi thăng cấp theo quy định.'
            ],
            [
                'name' => 'Cấp 9', 
                'color' => 'White', 
                'order_sequence' => 2, 
                'required_poomsae_code' => 'KT2', 
                'required_poomsae_name' => 'Kĩ thuật 2', 
                'description' => 'Đai trắng cấp 9',
                'belt_description' => 'Đai trắng Một vạch xanh dương',
                'minimum_time_months' => 3,
                'minimum_age' => 6,
                'age_requirement_note' => 'Từ 6 tuổi trở lên (Quý I của năm dự thi)'
            ],
            [
                'name' => 'Cấp 8', 
                'color' => 'White', 
                'order_sequence' => 3, 
                'required_poomsae_code' => 'TG1', 
                'required_poomsae_name' => 'Thái cực 1 Jang', 
                'description' => 'Đai trắng cấp 8',
                'belt_description' => 'Đai trắng Hai vạch xanh dương',
                'minimum_time_months' => 3,
                'minimum_age' => 6,
                'age_requirement_note' => 'Từ 6 tuổi trở lên (Quý II của năm dự thi)'
            ],
            [
                'name' => 'Cấp 7', 
                'color' => 'Yellow', 
                'order_sequence' => 4, 
                'required_poomsae_code' => 'TG2', 
                'required_poomsae_name' => 'Thái cực 2 Jang', 
                'description' => 'Đai vàng cấp 7',
                'belt_description' => 'Đai vàng',
                'minimum_time_months' => 3,
                'minimum_age' => 6,
                'age_requirement_note' => 'Từ 6 tuổi trở lên (Quý III của năm dự thi)'
            ],
            [
                'name' => 'Cấp 6', 
                'color' => 'Green', 
                'order_sequence' => 5, 
                'required_poomsae_code' => 'TG3', 
                'required_poomsae_name' => 'Thái cực 3 Jang', 
                'description' => 'Đai xanh lá cấp 6',
                'belt_description' => 'Xanh lá cây',
                'minimum_time_months' => 3,
                'minimum_age' => 6,
                'age_requirement_note' => 'Từ 6 tuổi trở lên (Quý IV của năm dự thi)'
            ],
            [
                'name' => 'Cấp 5', 
                'color' => 'Blue', 
                'order_sequence' => 6, 
                'required_poomsae_code' => 'TG4', 
                'required_poomsae_name' => 'Thái cực 4 Jang', 
                'description' => 'Đai xanh dương cấp 5',
                'belt_description' => 'Xanh dương',
                'minimum_time_months' => 3,
                'minimum_age' => 7,
                'age_requirement_note' => 'Từ 7 tuổi trở lên (Quý I của năm dự thi)'
            ],
            [
                'name' => 'Cấp 4', 
                'color' => 'Red', 
                'order_sequence' => 7, 
                'required_poomsae_code' => 'TG5', 
                'required_poomsae_name' => 'Thái cực 5 Jang', 
                'description' => 'Đai đỏ cấp 4',
                'belt_description' => 'Đai Đỏ',
                'minimum_time_months' => 3,
                'minimum_age' => 7,
                'age_requirement_note' => 'Từ 7 tuổi trở lên (Quý II của năm dự thi)'
            ],
            [
                'name' => 'Cấp 3', 
                'color' => 'Red', 
                'order_sequence' => 8, 
                'required_poomsae_code' => 'TG6', 
                'required_poomsae_name' => 'Thái cực 6 Jang', 
                'description' => 'Đai đỏ cấp 3',
                'belt_description' => 'Đai Đỏ Một vạch đen',
                'minimum_time_months' => 3,
                'minimum_age' => 7,
                'age_requirement_note' => 'Từ 7 tuổi trở lên (Quý III của năm dự thi)'
            ],
            // Cấp 2 - Thập đẳng (từ bảng 1)
            [
                'name' => 'Cấp 2', 
                'color' => 'Red', 
                'order_sequence' => 9, 
                'required_poomsae_code' => 'TG7', 
                'required_poomsae_name' => 'Thái cực 7 Jang', 
                'description' => 'Đai đỏ cấp 2',
                'belt_description' => 'Đai Đỏ Hai vạch đen',
                'minimum_time_months' => 3,
                'minimum_age' => 7,
                'age_requirement_note' => 'Từ 7 tuổi trở lên (Quý IV của năm dự thi)'
            ],
            [
                'name' => 'Cấp 1', 
                'color' => 'Red', 
                'order_sequence' => 10, 
                'required_poomsae_code' => 'TG8', 
                'required_poomsae_name' => 'Thái cực 8 Jang', 
                'description' => 'Đai đỏ cấp 1',
                'belt_description' => 'Đai Đỏ Ba vạch đen',
                'minimum_time_months' => 3,
                'minimum_age' => 8,
                'age_requirement_note' => 'Từ 8 tuổi trở lên (Quý I của năm dự thi)'
            ],
            [
                'name' => '1 Poom/Dan Nhất đẳng', 
                'color' => 'Black', 
                'order_sequence' => 11, 
                'required_poomsae_code' => 'KR', 
                'required_poomsae_name' => 'Koryo', 
                'description' => 'Đai đen 1 đẳng',
                'belt_description' => 'Đai đen đỏ/Đai đen 1 vạch trắng',
                'minimum_time_months' => 6,
                'minimum_age' => 8,
                'age_requirement_note' => 'Từ 8 tuổi trở lên (Quý III của năm dự thi)'
            ],
            [
                'name' => '2 Poom/Dan Nhị đẳng', 
                'color' => 'Black', 
                'order_sequence' => 12, 
                'required_poomsae_code' => 'KG', 
                'required_poomsae_name' => 'Keumgang', 
                'description' => 'Đai đen 2 đẳng',
                'belt_description' => 'Đai đen 2 vạch trắng',
                'minimum_time_months' => 12,
                'minimum_age' => 9,
                'age_requirement_note' => 'Từ 9 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => '3 Poom/Dan Tam đẳng', 
                'color' => 'Black', 
                'order_sequence' => 13, 
                'required_poomsae_code' => 'TB', 
                'required_poomsae_name' => 'Taebaek', 
                'description' => 'Đai đen 3 đẳng',
                'belt_description' => 'Đai đen 3 vạch trắng',
                'minimum_time_months' => 24,
                'minimum_age' => 15,
                'age_requirement_note' => 'Từ 15 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thi lên (4) Tứ đẳng', 
                'color' => 'Black', 
                'order_sequence' => 14, 
                'required_poomsae_code' => 'PW', 
                'required_poomsae_name' => 'Pyongwon', 
                'description' => 'Đai đen 4 đẳng',
                'belt_description' => 'Đai đen 4 vạch vàng',
                'minimum_time_months' => 36,
                'minimum_age' => 18,
                'age_requirement_note' => '18 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thi lên (5) Ngũ đẳng', 
                'color' => 'Black', 
                'order_sequence' => 15, 
                'required_poomsae_code' => 'SJ', 
                'required_poomsae_name' => 'Sipjin', 
                'description' => 'Đai đen 5 đẳng',
                'belt_description' => 'Đai đen 5 vạch vàng',
                'minimum_time_months' => 48,
                'minimum_age' => 22,
                'age_requirement_note' => '22 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thi lên (6) Lục đẳng', 
                'color' => 'Black', 
                'order_sequence' => 16, 
                'required_poomsae_code' => 'JT', 
                'required_poomsae_name' => 'Jitae', 
                'description' => 'Đai đen 6 đẳng',
                'belt_description' => 'Đai đen 6 vạch đỏ',
                'minimum_time_months' => 60,
                'minimum_age' => 30,
                'age_requirement_note' => '30 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thi lên (7) Thất đẳng', 
                'color' => 'Black', 
                'order_sequence' => 17, 
                'required_poomsae_code' => 'CK', 
                'required_poomsae_name' => 'Cheonkwon', 
                'description' => 'Đai đen 7 đẳng',
                'belt_description' => 'Đai đen 7 vạch đỏ',
                'minimum_time_months' => 72,
                'minimum_age' => 36,
                'age_requirement_note' => '36 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thi lên (8) Bát đẳng', 
                'color' => 'Black', 
                'order_sequence' => 18, 
                'required_poomsae_code' => 'HS', 
                'required_poomsae_name' => 'Hansoo', 
                'description' => 'Đai đen 8 đẳng',
                'belt_description' => 'Đai đen 8 vạch trắng',
                'minimum_time_months' => 96,
                'minimum_age' => 44,
                'age_requirement_note' => '44 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thi lên (9) Cửu đẳng', 
                'color' => 'Black', 
                'order_sequence' => 19, 
                'required_poomsae_code' => 'IY', 
                'required_poomsae_name' => 'Ilyeo', 
                'description' => 'Đai đen 9 đẳng',
                'belt_description' => 'Đai đen 9 vạch trắng',
                'minimum_time_months' => 108,
                'minimum_age' => 53,
                'age_requirement_note' => '53 tuổi trở lên (Tính theo năm thi)'
            ],
            [
                'name' => 'Thập đẳng', 
                'color' => 'Black', 
                'order_sequence' => 20, 
                'required_poomsae_code' => 'IY', 
                'required_poomsae_name' => 'Ilyeo', 
                'description' => 'Đai đen 10 đẳng',
                'belt_description' => 'Đai đen 10 vạch trắng',
                'minimum_time_months' => null,
                'minimum_age' => null,
                'age_requirement_note' => 'Do Kukkiwon quy định'
            ],
        ];
        foreach ($capDaiData as $data) {
            DB::table('cap_dai')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

