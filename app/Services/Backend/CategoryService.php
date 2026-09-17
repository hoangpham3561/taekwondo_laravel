<?php
namespace App\Services\Backend;

use App\Enums\CategoryStatusEnum;
use App\Models\Category;
use App\Services\BaseService;
use DB;
use Illuminate\Http\Request;

class CategoryService extends BaseService
{

    public function getAll(Request $request)
    {
        $filters = $request->all();

        // perPage handle
        if (! empty($filters['perPage']) && $filters['perPage'] > 15) {
            $this->perPage = $filters['perPage'];
        }

        $query = Category::query()->with(['children'])->where('parent_id', 0);

        if (! empty($filters['status'])) {
            $query = $query->when($filters['status'] === CategoryStatusEnum::ACTIVE, function ($q) use ($filters) {
                                return $q->where('status', $filters['status']);
                            })
                            ->when($filters['status'] === CategoryStatusEnum::INACTIVE, function ($q) use ($filters) {
                                return $q->where('status', $filters['status']);
                            })
                            ->when($filters['status'] === CategoryStatusEnum::DELETED, function ($q) use ($filters) {
                                return $q->where('status', $filters['status']);
                            });

        }

        return $query->orderBy('id', 'DESC')->paginate($this->perPage);
    }

    public function store($request)
    {
        return Category::query()->create($request->validated());
    }

    public function update($request, $id)
    {
        $model = new Category();

        $category = $model->withTrashed()->with(['children'])->findOrFail($id);
        $data = $category->fill($request->validated());

        // If status is deleted, use soft delete
        // Otherwise, clear deleted_by & deleted_at
        if ($request['status'] !== CategoryStatusEnum::DELETED) {
            $data->deleted_at = null;
            $data->deleted_by = null;

            $updateData = $data->toArray();
            return $category->update($updateData);
        } else {
            return $category->delete();
        }
    }

    public function delete($model)
    {
        try {
            DB::beginTransaction();
            $model->delete();
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }

    public function groupByStatus()
    {
        $query = Category::query()->orderBy('id', 'DESC')->get();

        return $query->countBy('status');
    }

    public function getParentCategories($id = 0, $options = []): array
    {
        if($options['task'] == 'category-create') {
            $query = Category::query()->with(['children'])->where([
                'parent_id' => 0,
                'status'    => CategoryStatusEnum::ACTIVE
            ]);
        }

        if($options['task'] == 'category-edit') {
            $query = Category::query()->with(['children'])->where([
                ['parent_id', '=', 0],
                ['status', '=', CategoryStatusEnum::ACTIVE],
                ['id', '!=', $id]
            ]);
        }

        return $query->get()->toArray();
    }
}
