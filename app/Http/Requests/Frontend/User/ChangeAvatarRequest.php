<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAvatarRequest extends FormRequest
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
            'images' => [
                'required',
                'image',
                'mimes:png,jpeg,gif',
                'mimetypes:image/jpeg,image/png,image/gif',
                'file_extension:jpeg,png,gif',
            ],
        ];
    }

    public function messages()
    {
        $messages = [
            'file_extension' => 'File images not extension correct, please try again with file correct',
        ];

        return $messages;
    }
}
