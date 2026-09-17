<?php

namespace App\Http\Requests\Frontend\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Parse wallets_data từ JSON string thành array
        $walletsData = json_decode($this->input('wallets_data'), true);

        if ($walletsData && is_array($walletsData)) {
            // Merge wallets vào request để validation có thể kiểm tra
            $this->merge([
                'wallets' => $walletsData
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:500',
            'payment_method' => 'required|string|in:0,1',
            'shipping_method' => 'required|string|in:0,1',
            'shipping_fee' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ và tên',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ nhận hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
            'shipping_method.required' => 'Vui lòng chọn phương thức vận chuyển',
            'shipping_method.in' => 'Phương thức vận chuyển không hợp lệ',
        ];
    }
}
