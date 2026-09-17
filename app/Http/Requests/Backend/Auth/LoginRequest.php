<?php

namespace App\Http\Requests\Backend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\CaptchaService;

class LoginRequest extends FormRequest
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
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|string|size:5',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $captchaService = new CaptchaService();
            $userAnswer = $this->input('captcha');

            if (!$captchaService->verify($userAnswer)) {
                $validator->errors()->add('captcha', 'Mã CAPTCHA không đúng. Vui lòng thử lại.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'captcha.required' => 'Vui lòng nhập mã CAPTCHA',
            'captcha.size' => 'Mã CAPTCHA phải có 5 chữ số',
        ];
    }
}
