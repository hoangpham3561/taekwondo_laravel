<?php

namespace App\Http\Requests\Backend\ProfileSetting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $admin = Auth::guard('admin')->user();
        $userId = $admin ? $admin->id : null;

        return [
            'ho_va_ten' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('huan_luyen_vien', 'email')->ignore($userId),
            ],
            'phone' => 'nullable|string|max:20',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'ho_va_ten.required' => 'Vui lòng nhập họ và tên.',
            'ho_va_ten.string' => 'Họ và tên phải là chuỗi ký tự.',
            'ho_va_ten.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'photo_url.image' => 'File phải là hình ảnh.',
            'photo_url.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'photo_url.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];
    }
}
