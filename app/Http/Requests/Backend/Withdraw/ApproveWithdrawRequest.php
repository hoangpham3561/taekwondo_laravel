<?php

namespace App\Http\Requests\Backend\Withdraw;

use Illuminate\Foundation\Http\FormRequest;

class ApproveWithdrawRequest extends FormRequest
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
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'receipt_image.required' => 'Vui lòng upload hóa đơn chuyển khoản',
            'receipt_image.image' => 'File phải là hình ảnh',
            'receipt_image.mimes' => 'File phải có định dạng: jpeg, png, jpg, gif',
            'receipt_image.max' => 'Kích thước file không được vượt quá 5MB',
        ];
    }
}
