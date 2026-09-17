<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VoSinhTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return sample data rows
        return [
            [
                'Nguyễn Văn A',
                '2010-05-15',
                'HV_nguyenvanA_20240101',
                'CLB_00468',
                'DNAI',
                7,
                'Trắng',
                'Nam',
                'nguyenvana@example.com',
                '0123456789',
                '123 Đường ABC, Quận 1, TP.HCM',
                'Nguyễn Văn Bố',
                '0987654321',
                1,
                '123456@LV23',
            ],
            [
                'Trần Thị B',
                '2012-08-20',
                'HV_tranthiB_20240102',
                'CLB_00468',
                'DNAI',
                7,
                'Vàng',
                'Nữ',
                'tranthib@example.com',
                '0987654321',
                '456 Đường XYZ, Quận 2, TP.HCM',
                'Trần Thị Mẹ',
                '0123456789',
                1,
                '123456@LV23',
            ],
        ];
    }

    /**
     * @return array
    */
    public function headings(): array
    {
        return [
            'ho_va_ten',
            'ngay_thang_nam_sinh',
            'ma_hoi_vien',
            'ma_clb',
            'ma_don_vi',
            'quyen_so',
            'cap_dai',
            'gioi_tinh',
            'email',
            'phone',
            'address',
            'emergency_contact_name',
            'emergency_contact_phone',
            'active_status',
            'password',
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]],
        ];
    }
}
