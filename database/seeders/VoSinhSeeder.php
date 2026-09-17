<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoSinhSeeder extends Seeder
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
        $voSinhData = [
            [
                'ho_va_ten' => 'Hoàng Phạm Bảo Anh',
                'ngay_thang_nam_sinh' => '2016-02-28',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 1,
                'gioi_tinh' => 'Nữ',
                'email' => 'anhhpb@example.com',
                'phone' => '0123456789',
                'password' => '123456@LV23',
                'profile_image_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
            ],
            [
                'ho_va_ten' => 'Nguyễn Thị Minh Châu',
                'ngay_thang_nam_sinh' => '2015-07-03',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 1,
                'gioi_tinh' => 'Nữ',
                'email' => 'chauntm@example.com',
                'phone' => '0123456790',
                'password' => '123456@LV23',
                'profile_image_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
            ],
            [
                'ho_va_ten' => 'Lục Minh Châu',
                'ngay_thang_nam_sinh' => '2010-05-10',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 1,
                'gioi_tinh' => 'Nữ',
                'email' => 'chaulm@example.com',
                'phone' => '0123456791',
                'password' => '123456@LV23',
                'profile_image_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
            ],
            [
                'ho_va_ten' => 'Nguyễn Minh Châu',
                'ngay_thang_nam_sinh' => '2014-08-15',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 1,
                'gioi_tinh' => 'Nữ',
                'email' => 'chaunm@example.com',
                'phone' => '0123456792',
                'password' => '123456@LV23',
                'profile_image_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
            ],
            [
                'ho_va_ten' => 'Phạm Minh Châu',
                'ngay_thang_nam_sinh' => '2011-12-20',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 1,
                'gioi_tinh' => 'Nữ',
                'email' => 'chauphm@example.com',
                'phone' => '0123456793',
                'password' => '123456@LV23',
                'profile_image_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
            ],
            [
                'ho_va_ten' => 'Đoàn Trần Thiên Phương',
                'ngay_thang_nam_sinh' => '2012-06-25',
                'ma_clb' => 'CLB_00468',
                'ma_don_vi' => 'DNAI',
                'quyen_so' => 7,
                'cap_dai_id' => 1,
                'gioi_tinh' => 'Nữ',
                'email' => 'phuongdtt@example.com',
                'phone' => '0123456794',
                'password' => '123456@LV23',
                'profile_image_url' => 'client/images/users/user-40.jpg',
                'images' => '["client/images/users/user-40.jpg"]',
            ],
        ];

        foreach ($voSinhData as $data) {
            $maHoiVien = $this->generateMemberCode($data['ho_va_ten'], $data['ngay_thang_nam_sinh'], 'HV');
            
            DB::table('vo_sinh')->insert([
                'ho_va_ten' => $data['ho_va_ten'],
                'ngay_thang_nam_sinh' => $data['ngay_thang_nam_sinh'],
                'ma_hoi_vien' => $maHoiVien,
                'ma_clb' => $data['ma_clb'],
                'ma_don_vi' => $data['ma_don_vi'],
                'quyen_so' => $data['quyen_so'],
                'cap_dai_id' => $data['cap_dai_id'],
                'gioi_tinh' => $data['gioi_tinh'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'profile_image_url' => $data['profile_image_url'],
                'images' => $data['images'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

