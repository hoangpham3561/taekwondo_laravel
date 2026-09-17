<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiQuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baiQuyenData = [
            // Taeguek Poomsae (Thái cực) - Cơ bản
            ['ten_bai_quyen_vietnamese' => 'Thái cực 1 Jang', 'ten_bai_quyen_english' => 'Taegeuk Il-jang', 'ten_bai_quyen_korean' => '태극 1장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản đầu tiên, tượng trưng cho Trời', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về tư thế cơ bản và kỹ thuật đấm đá'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 2 Jang', 'ten_bai_quyen_english' => 'Taegeuk Ee-jang', 'ten_bai_quyen_korean' => '태극 2장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ hai, tượng trưng cho Đất', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về di chuyển và phòng thủ'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 3 Jang', 'ten_bai_quyen_english' => 'Taegeuk Sam-jang', 'ten_bai_quyen_korean' => '태극 3장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ ba, tượng trưng cho Lửa', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về tấn công và phản công'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 4 Jang', 'ten_bai_quyen_english' => 'Taegeuk Sa-jang', 'ten_bai_quyen_korean' => '태극 4장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ tư, tượng trưng cho Gió', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về tốc độ và linh hoạt'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 5 Jang', 'ten_bai_quyen_english' => 'Taegeuk Oh-jang', 'ten_bai_quyen_korean' => '태극 5장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ năm, tượng trưng cho Nước', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự mềm mại và uyển chuyển'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 6 Jang', 'ten_bai_quyen_english' => 'Taegeuk Yook-jang', 'ten_bai_quyen_korean' => '태극 6장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ sáu, tượng trưng cho Sơn', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự vững chắc và ổn định'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 7 Jang', 'ten_bai_quyen_english' => 'Taegeuk Chil-jang', 'ten_bai_quyen_korean' => '태극 7장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ bảy, tượng trưng cho Lôi', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sức mạnh và bùng nổ'],
            ['ten_bai_quyen_vietnamese' => 'Thái cực 8 Jang', 'ten_bai_quyen_english' => 'Taegeuk Pal-jang', 'ten_bai_quyen_korean' => '태극 8장', 'cap_do' => 'Cơ bản', 'mo_ta' => 'Bài quyền cơ bản thứ tám, tượng trưng cho Phong', 'so_dong_tac' => 20, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự nhẹ nhàng và bay bổng'],
            // Black Belt Poomsae (Đai đen)
            ['ten_bai_quyen_vietnamese' => 'Koryo', 'ten_bai_quyen_english' => 'Koryo', 'ten_bai_quyen_korean' => '고려', 'cap_do' => 'Trung cấp', 'mo_ta' => 'Bài quyền đai đen đầu tiên, tên của triều đại Koryo', 'so_dong_tac' => 30, 'thoi_gian_thuc_hien' => 60, 'khoi_luong_ly_thuyet' => 'Lý thuyết về lịch sử và truyền thống'],
            ['ten_bai_quyen_vietnamese' => 'Keumgang', 'ten_bai_quyen_english' => 'Keumgang', 'ten_bai_quyen_korean' => '금강', 'cap_do' => 'Trung cấp', 'mo_ta' => 'Bài quyền đai đen thứ hai, tên của ngọn núi Keumgang', 'so_dong_tac' => 27, 'thoi_gian_thuc_hien' => 55, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự kiên cường và bền bỉ'],
            ['ten_bai_quyen_vietnamese' => 'Taebaek', 'ten_bai_quyen_english' => 'Taebaek', 'ten_bai_quyen_korean' => '태백', 'cap_do' => 'Trung cấp', 'mo_ta' => 'Bài quyền đai đen thứ ba, tên của ngọn núi Taebaek', 'so_dong_tac' => 26, 'thoi_gian_thuc_hien' => 50, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự cao quý và thanh khiết'],
            ['ten_bai_quyen_vietnamese' => 'Pyongwon', 'ten_bai_quyen_english' => 'Pyongwon', 'ten_bai_quyen_korean' => '평원', 'cap_do' => 'Trung cấp', 'mo_ta' => 'Bài quyền đai đen thứ tư, tên của đồng bằng', 'so_dong_tac' => 21, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự rộng lớn và bao dung'],
            ['ten_bai_quyen_vietnamese' => 'Sipjin', 'ten_bai_quyen_english' => 'Sipjin', 'ten_bai_quyen_korean' => '십진', 'cap_do' => 'Nâng cao', 'mo_ta' => 'Bài quyền đai đen thứ năm, tên của số 10', 'so_dong_tac' => 28, 'thoi_gian_thuc_hien' => 55, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự hoàn thiện và toàn diện'],
            ['ten_bai_quyen_vietnamese' => 'Jitae', 'ten_bai_quyen_english' => 'Jitae', 'ten_bai_quyen_korean' => '지태', 'cap_do' => 'Nâng cao', 'mo_ta' => 'Bài quyền đai đen thứ sáu, tên của Trái Đất', 'so_dong_tac' => 28, 'thoi_gian_thuc_hien' => 55, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự ổn định và vững chắc'],
            ['ten_bai_quyen_vietnamese' => 'Cheonkwon', 'ten_bai_quyen_english' => 'Cheonkwon', 'ten_bai_quyen_korean' => '천권', 'cap_do' => 'Nâng cao', 'mo_ta' => 'Bài quyền đai đen thứ bảy, tên của Bầu trời', 'so_dong_tac' => 26, 'thoi_gian_thuc_hien' => 50, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự cao xa và vô tận'],
            ['ten_bai_quyen_vietnamese' => 'Hansoo', 'ten_bai_quyen_english' => 'Hansoo', 'ten_bai_quyen_korean' => '한수', 'cap_do' => 'Nâng cao', 'mo_ta' => 'Bài quyền đai đen thứ tám, tên của Nước', 'so_dong_tac' => 27, 'thoi_gian_thuc_hien' => 55, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự linh hoạt và thích ứng'],
            ['ten_bai_quyen_vietnamese' => 'Ilyeo', 'ten_bai_quyen_english' => 'Ilyeo', 'ten_bai_quyen_korean' => '일여', 'cap_do' => 'Nâng cao', 'mo_ta' => 'Bài quyền đai đen thứ chín, tên của Sự thống nhất', 'so_dong_tac' => 23, 'thoi_gian_thuc_hien' => 45, 'khoi_luong_ly_thuyet' => 'Lý thuyết về sự hòa hợp và nhất thể'],
        ];

        foreach ($baiQuyenData as $data) {
            DB::table('bai_quyen')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

