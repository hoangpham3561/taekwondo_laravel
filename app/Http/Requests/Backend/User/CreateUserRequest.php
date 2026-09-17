<?php

namespace App\Http\Requests\Backend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_va_ten' => ['required', 'string', 'max:100'],
            'ngay_thang_nam_sinh' => ['required', 'date'],
            'ma_hoi_vien' => ['required', 'string', 'max:50', 'unique:vo_sinh,ma_hoi_vien'],
            'ma_clb' => ['required', 'string', 'max:20'],
            'ma_don_vi' => ['required', 'string', 'max:20'],
            'quyen_so' => ['required', 'integer', 'min:1'],
            'cap_dai_id' => ['required', 'exists:cap_dai,id'],
            'gioi_tinh' => ['required', Rule::in(['Nam', 'Nữ'])],
            'email' => ['nullable', 'email', 'max:100', 'unique:vo_sinh,email'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:15'],
            'active_status' => ['nullable', 'in:0,1'],
            'password' => ['nullable', 'string', 'min:6'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('email') === '') {
            $this->merge(['email' => null]);
        }
    }
}
