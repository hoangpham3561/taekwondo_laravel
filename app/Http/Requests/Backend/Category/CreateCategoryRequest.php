<?php

namespace App\Http\Requests\Backend\Category;
use App\Helpers\BaseHelper;
use App\Enums\CategoryStatusEnum;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'slug'          => empty($this->slug) ? Str::slug($this->name) : Str::slug($this->slug),
            'parent_id'     => (!empty($this->parent_id)) ? $this->parent_id : 0,
        ]);
    }

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
            'name'  => [
                'required',
                'max:255',
                'unique:category,name'
            ],
            'slug' => [
              'required',
              'unique:category,slug',
            ],
            'parent_id' => 'sometimes|nullable|numeric',
            'status' => [
                'required',
                Rule::in(CategoryStatusEnum::getValues())
            ]
        ];
    }
}
