<?php
namespace App\Services\Backend;

use App\Enums\NewsStatusEnum;
use App\Models\News;
use App\Services\BaseService;
use App\Traits\Upload;
use DB;
use Illuminate\Http\Request;

class NewsService extends BaseService
{

    use Upload;

    public function getAll(Request $request)
    {
        $filters = $request->all();

        // perPage handle
        if (! empty($filters['perPage']) && $filters['perPage'] > 15) {
            $this->perPage = $filters['perPage'];
        }

        $query = News::query()->with('category');

        if (! empty($filters['status'])) {
            $query = $query->when($filters['status'] === NewsStatusEnum::ACTIVE, function ($q) use ($filters) {
                                return $q->where('status', $filters['status']);
                            })
                            ->when($filters['status'] === NewsStatusEnum::INACTIVE, function ($q) use ($filters) {
                                return $q->where('status', $filters['status']);
                            });
        }

        return $query->orderBy('id', 'DESC')->paginate($this->perPage);
    }

    public function store($request)
    {
        $data = $request->all();

        if(!empty($request['images'])) {
            $data['images'] = $this->doUpload('jpg|png|gif','upload', 'images');
        }
        return News::create($data);
    }

    public function update($request, $id)
    {
        $data = $request->all();
        $model = new News();
        $news = $model->with('category')->findOrFail($id);

        if(!empty($request['images'])) {
            $this->deleteImage('upload', $news['images']);
            $data['images'] = $this->doUpload('jpg|png|gif','upload', 'images');
        }

        if (($data['status'] ?? null) === NewsStatusEnum::DELETED) {
            return $news->delete();
        }

        return $news->update($data);
    }

    public function delete($model)
    {
        try {
            DB::beginTransaction();
            $this->deleteImage('upload', $model['images']);
            $model->delete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }
//
    public function groupByStatus()
    {
        $query = News::query()->with('category')->orderBy('id', 'DESC')->get();

        return $query->countBy('status');
    }
}
