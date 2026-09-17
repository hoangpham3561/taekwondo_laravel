<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignUpRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/', // Chỉ cho phép chữ thường, số và dấu gạch dưới
                Rule::unique('user', 'UserName'), // Đổi từ users sang user, username sang UserName
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user', 'Email'), // Đổi từ users sang user, email sang Email
            ],
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'ref_id' => [
                'nullable',
                Rule::exists('user', 'UserName')->where('Active', 'Y'), // Đổi từ users sang user, username sang UserName
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     * Tự động chuyển username về chữ thường trước khi validate
     */
    protected function prepareForValidation()
    {
        if ($this->has('username')) {
            $this->merge([
                'username' => strtolower($this->input('username')),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => 'Vui lòng nhập tên người dùng',
            'username.regex' => 'Tên người dùng chỉ được chứa chữ thường, số và dấu gạch dưới. Không được có khoảng cách, dấu câu hoặc chữ in hoa.',
            'username.unique' => 'Tên người dùng đã tồn tại',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'ref_id.exists' => 'Mã giới thiệu không tồn tại hoặc không hợp lệ',
        ];
    }
}
