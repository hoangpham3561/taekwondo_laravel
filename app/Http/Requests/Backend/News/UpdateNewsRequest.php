<?php

namespace App\Http\Requests\Backend\News;
use App\Enums\NewsStatusEnum;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $newsId = $this->route('news');
        $newsId = is_object($newsId) ? $newsId->id : $newsId;

        $baseSlug = empty($this->slug) ? Str::slug($this->title) : Str::slug($this->slug);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'news';

        $this->merge([
            'slug' => $this->makeUniqueSlug($baseSlug, $newsId),
        ]);
    }

    private function makeUniqueSlug(string $baseSlug, $ignoreId = null): string
    {
        $slug = $baseSlug;
        $counter = 1;

        $query = News::query();
        if (!empty($ignoreId)) {
            $query->where('id', '!=', $ignoreId);
        }

        while ((clone $query)->where('slug', $slug)->exists()) {
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
              'unique:tin_tuc,slug,' . $this->news,
            ],
            'content' => [
                'required',
            ],
            'cate_id' => 'required|numeric|exists:category,id',
            'images' => [
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
