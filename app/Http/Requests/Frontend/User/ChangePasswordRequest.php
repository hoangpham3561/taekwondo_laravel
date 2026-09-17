<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password',
            'new_password_confirmation' => 'required|min:6|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.different' => 'Mật khẩu mới phải khác mật khẩu cũ.',
            'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'new_password_confirmation.min' => 'Mật khẩu xác nhận phải có ít nhất 6 ký tự.',
            'new_password_confirmation.same' => 'Mật khẩu xác nhận không khớp với mật khẩu mới.',
        ];
    }
}
