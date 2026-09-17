<?php

namespace App\Imports;

use App\Models\VoSinh;
use App\Models\CapDai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Carbon\Carbon;

class VoSinhImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if ma_hoi_vien already exists
        $existingVoSinh = VoSinh::where('ma_hoi_vien', $row['ma_hoi_vien'] ?? '')->first();
        if ($existingVoSinh) {
            return null; // Skip duplicate
        }

        // Map cấp đai từ tên sang ID
        $capDaiId = 1; // Default to first cap_dai
        if (!empty($row['cap_dai'])) {
            $capDai = CapDai::where('name', 'like', '%' . trim($row['cap_dai']) . '%')->first();
            if ($capDai) {
                $capDaiId = $capDai->id;
            }
        }

        // Parse ngày sinh
        $ngaySinh = null;
        if (!empty($row['ngay_thang_nam_sinh'])) {
            try {
                // Hỗ trợ nhiều format: YYYY-MM-DD, DD/MM/YYYY, DD-MM-YYYY
                $dateStr = trim($row['ngay_thang_nam_sinh']);
                if (is_numeric($dateStr)) {
                    // Excel date serial number
                    $ngaySinh = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$dateStr));
                } else {
                    // Try different date formats
                    $ngaySinh = Carbon::createFromFormat('Y-m-d', $dateStr);
                }
            } catch (\Exception $e) {
                try {
                    // Try DD/MM/YYYY format
                    $ngaySinh = Carbon::createFromFormat('d/m/Y', $dateStr);
                } catch (\Exception $e2) {
                    try {
                        // Try DD-MM-YYYY format
                        $ngaySinh = Carbon::createFromFormat('d-m-Y', $dateStr);
                    } catch (\Exception $e3) {
                        $ngaySinh = null;
                    }
                }
            }
        }

        return new VoSinh([
            'ho_va_ten' => trim($row['ho_va_ten'] ?? ''),
            'ngay_thang_nam_sinh' => $ngaySinh,
            'ma_hoi_vien' => trim($row['ma_hoi_vien'] ?? ''),
            'ma_clb' => trim($row['ma_clb'] ?? ''),
            'ma_don_vi' => trim($row['ma_don_vi'] ?? ''),
            'quyen_so' => !empty($row['quyen_so']) ? (int)$row['quyen_so'] : 1,
            'cap_dai_id' => $capDaiId,
            'gioi_tinh' => !empty($row['gioi_tinh']) ? trim($row['gioi_tinh']) : 'Nam',
            'email' => !empty($row['email']) ? trim($row['email']) : null,
            'phone' => !empty($row['phone']) ? trim($row['phone']) : null,
            'address' => !empty($row['address']) ? trim($row['address']) : null,
            'emergency_contact_name' => !empty($row['emergency_contact_name']) ? trim($row['emergency_contact_name']) : null,
            'emergency_contact_phone' => !empty($row['emergency_contact_phone']) ? trim($row['emergency_contact_phone']) : null,
            'active_status' => isset($row['active_status']) ? (bool)$row['active_status'] : true,
            'password' => !empty($row['password']) ? trim($row['password']) : '123@LV23', // Default password
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'ho_va_ten' => 'required|string|max:100',
            'ma_hoi_vien' => 'required|string|max:50',
            'ma_clb' => 'required|string|max:20',
            'ma_don_vi' => 'required|string|max:20',
            'quyen_so' => 'nullable|integer|min:1',
            'gioi_tinh' => 'nullable|in:Nam,Nữ',
        ];
    }

    /**
     * Custom validation attributes
     */
    public function customValidationAttributes(): array
    {
        return [
            'ho_va_ten' => 'Họ và tên',
            'ma_hoi_vien' => 'Mã hội viên',
            'ma_clb' => 'Mã CLB',
            'ma_don_vi' => 'Mã đơn vị',
            'quyen_so' => 'Quyền số',
            'gioi_tinh' => 'Giới tính',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'ho_va_ten.required' => 'Họ và tên là bắt buộc',
            'ma_hoi_vien.required' => 'Mã hội viên là bắt buộc',
            'ma_hoi_vien.unique' => 'Mã hội viên đã tồn tại',
            'ma_clb.required' => 'Mã CLB là bắt buộc',
            'ma_don_vi.required' => 'Mã đơn vị là bắt buộc',
        ];
    }
}
