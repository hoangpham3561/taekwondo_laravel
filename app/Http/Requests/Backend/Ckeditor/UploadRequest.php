<?php

namespace App\Http\Requests\Backend\Ckeditor;

use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'upload' => [
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
