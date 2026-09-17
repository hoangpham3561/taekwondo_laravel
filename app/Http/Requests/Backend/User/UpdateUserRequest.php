<?php

namespace App\Http\Requests\Backend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->resolveVoSinhId();

        return [
            'ho_va_ten' => ['required', 'string', 'max:100'],
            'ngay_thang_nam_sinh' => ['required', 'date'],
            'ma_hoi_vien' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vo_sinh', 'ma_hoi_vien')->ignore($userId),
            ],
            'ma_clb' => ['required', 'string', 'max:20'],
            'ma_don_vi' => ['required', 'string', 'max:20'],
            'quyen_so' => ['required', 'integer', 'min:1'],
            'cap_dai_id' => ['required', 'exists:cap_dai,id'],
            'gioi_tinh' => ['required', Rule::in(['Nam', 'Nữ'])],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('vo_sinh', 'email')->ignore($userId),
            ],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:15'],
            'active_status' => ['nullable', 'in:0,1'],
            'password' => ['nullable', 'string', 'min:6'],
            'user_id' => ['sometimes', 'nullable', 'integer'],
        ];
    }

    protected function resolveVoSinhId(): int
    {
        $id = $this->input('user_id')
            ?? $this->route('user');

        if (!$id && $this->route()) {
            $routeParams = $this->route()->parameters();
            $id = $routeParams['user'] ?? $routeParams['id'] ?? null;
        }

        return (int) $id;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('user_id')) {
            $userId = $this->route('user');
            if ($userId !== null) {
                $this->merge(['user_id' => (int) $userId]);
            }
        } else {
            $this->merge(['user_id' => (int) $this->input('user_id')]);
        }

        if ($this->input('email') === '') {
            $this->merge(['email' => null]);
        }
    }
}
