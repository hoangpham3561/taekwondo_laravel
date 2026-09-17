<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HuanLuyenVienSeeder extends Seeder
{
    /**
     * Generate member code (mã hội viên)
     */
    private function generateMemberCode(string $fullName, string $birthDate, string $memberType): string
    {
        // Normalize name
        $fullName = trim(preg_replace('/\s+/', ' ', $fullName));
        $nameParts = explode(' ', $fullName);
        $nameCount = count($nameParts);
        
        // Last name (last part) - convert to non-accented
        $lastName = $this->removeVietnameseAccents(mb_strtolower($nameParts[$nameCount - 1], 'UTF-8'));
        
        // Middle names initials - convert to non-accented
        $initials = $this->removeVietnameseAccents(mb_strtolower(mb_substr($nameParts[0], 0, 1, 'UTF-8'), 'UTF-8'));
        for ($i = 1; $i < $nameCount - 1; $i++) {
            $initials .= $this->removeVietnameseAccents(mb_strtolower(mb_substr($nameParts[$i], 0, 1, 'UTF-8'), 'UTF-8'));
        }
        
        // Generate final code
        $date = date('dmy', strtotime($birthDate));
        return "{$memberType}_{$lastName}{$initials}_{$date}";
    }

    /**
     * Remove Vietnamese accents from string
     */
    private function removeVietnameseAccents(string $str): string
    {
        $accents = [
            'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
            'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
            'ì','í','ị','ỉ','ĩ',
            'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
            'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
            'ỳ','ý','ỵ','ỷ','ỹ',
            'đ',
            'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
            'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
            'Ì','Í','Ị','Ỉ','Ĩ',
            'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
            'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
            'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
            'Đ'
        ];
        $noAccents = [
            'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
            'e','e','e','e','e','e','e','e','e','e','e',
            'i','i','i','i','i',
            'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
            'u','u','u','u','u','u','u','u','u','u','u',
            'y','y','y','y','y',
            'd',
            'A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A',
            'E','E','E','E','E','E','E','E','E','E','E',
            'I','I','I','I','I',
            'O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O',
            'U','U','U','U','U','U','U','U','U','U','U',
            'Y','Y','Y','Y','Y',
            'D'
        ];
        return str_replace($accents, $noAccents, $str);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hlvData = [
            [
                'ma_hoi_vien' => 'HLV_tientv_150385',
                'ho_va_ten' => 'Trần Văn Tiến',
                'ngay_thang_nam_sinh' => '1985-03-15',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 9,
                'cap_dai_id' => 12,
                'gioi_tinh' => 'Nam',
                'email' => 'thaytien@dongphu.com',
                'password' => '123456@LV23',
                'role' => 'owner',
                'phone' => '0987654321',
                'photo_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
                'is_active' => true,
            ],
            [
                'ma_hoi_vien' => 'HLV_huongtt_220790',
                'ho_va_ten' => 'Trần Thị Hương',
                'ngay_thang_nam_sinh' => '1990-07-22',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 8,
                'cap_dai_id' => 10,
                'gioi_tinh' => 'Nữ',
                'email' => 'huongtt@dongphu.com',
                'password' => '123456@LV23',
                'role' => 'admin',
                'phone' => '0987654322',
                'photo_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
                'is_active' => true,
            ],
            [
                'ma_hoi_vien' => 'HLV_duclm_101288',
                'ho_va_ten' => 'Lê Minh Đức',
                'ngay_thang_nam_sinh' => '1988-12-10',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 8,
                'gioi_tinh' => 'Nam',
                'email' => 'duclm@dongphu.com',
                'password' => '123456@LV23',
                'role' => 'admin',
                'phone' => '0987654323',
                'photo_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
                'is_active' => true,
            ],
            [
                'ma_hoi_vien' => 'HLV_tandt_180587',
                'ho_va_ten' => 'Đoàn Tiến Tân',
                'ngay_thang_nam_sinh' => '1987-05-18',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 8,
                'cap_dai_id' => 10,
                'gioi_tinh' => 'Nam',
                'email' => 'thaytan@dongphu.com',
                'password' => '123456@LV23',
                'role' => 'admin',
                'phone' => '0987654324',
                'photo_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
                'is_active' => true,
            ],
        ];

        foreach ($hlvData as $data) {
            // Use ma_hoi_vien from data if set, otherwise generate from name + birthdate
            $maHoiVien = $data['ma_hoi_vien'] ?? $this->generateMemberCode($data['ho_va_ten'], $data['ngay_thang_nam_sinh'], 'HLV');

            DB::table('huan_luyen_vien')->insert([
                'ma_hoi_vien' => $maHoiVien,
                'ho_va_ten' => $data['ho_va_ten'],
                'ngay_thang_nam_sinh' => $data['ngay_thang_nam_sinh'],
                'ma_clb' => $data['ma_clb'],
                'ma_don_vi' => $data['ma_don_vi'],
                'quyen_so' => $data['quyen_so'],
                'cap_dai_id' => $data['cap_dai_id'],
                'gioi_tinh' => $data['gioi_tinh'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
                'phone' => $data['phone'],
                'photo_url' => $data['photo_url'],
                'images' => $data['images'],
                'is_active' => $data['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

