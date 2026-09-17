<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'new_email' => [
                'required',
                'email',
                'max:100',
                'different:current_email',
                // Rule::unique('user', 'Email'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'new_email.required' => 'Vui lòng nhập email mới',
            'new_email.email' => 'Email không hợp lệ',
            'new_email.different' => 'Email mới phải khác email hiện tại',
            // 'new_email.unique' => 'Email này đã được sử dụng',
        ];
    }
}