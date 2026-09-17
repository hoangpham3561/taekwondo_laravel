<?php

namespace App\Http\Requests\Backend\News;
use App\Enums\NewsStatusEnum;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        // Always generate slug from title in create flow.
        $baseSlug = Str::slug((string) $this->title);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'news';

        $this->merge([
            'slug' => $this->makeUniqueSlug($baseSlug),
        ]);
    }

    private function makeUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while (News::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
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
            'title'  => [
                'required',
                'max:255',
            ],
            'slug' => [
              'required',
              'unique:tin_tuc,slug',
            ],
            'content' => [
                'required',
            ],
            'cate_id' => 'required|numeric|exists:category,id',
//            'images' => 'required|mimes:png,jpeg,gif|mimetypes:image/jpeg,image/png,image/gif',
            'images' => [
                'required',
                'image',
                'mimes:png,jpeg,gif',
                'mimetypes:image/jpeg,image/png,image/gif',
            ],
            'status' => [
                'required',
                Rule::in(NewsStatusEnum::getValues())
            ]
        ];
    }

    public function messages()
    {
        return [];
    }
}
