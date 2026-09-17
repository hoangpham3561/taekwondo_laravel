<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Foundation\Http\FormRequest;

class KycAccountRequest extends FormRequest
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
            'name' => 'required',
            'identity_card' => 'required',
            'identity_card_img' => [
                'required',
                'image',
                'mimes:png,jpg,jpeg,gif',
                'mimetypes:image/jpeg,image/png,image/gif,image/jpg',
                'file_extension:jpeg,png,gif,jpg',
            ],
            'identity_card_back_img' => [
                'required',
                'image',
                'mimes:png,jpg,jpeg,gif',
                'mimetypes:image/jpeg,image/png,image/gif,image/jpg',
                'file_extension:jpeg,png,gif,jpg',
            ],
            'identity_card_selfie_img' => [
                'required',
                'image',
                'mimes:png,jpg,jpeg,gif',
                'mimetypes:image/jpeg,image/png,image/gif,image/jpg',
                'file_extension:jpeg,png,gif,jpg',
            ],
        ];
    }
}
