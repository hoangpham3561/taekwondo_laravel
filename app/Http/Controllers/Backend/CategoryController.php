<?php

namespace App\Http\Controllers\Backend;

use App\Enums\CategoryStatusEnum;
use App\Http\Requests\Backend\Category\CreateCategoryRequest;
use App\Http\Requests\Backend\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use App\Services\Backend\CategoryService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    private $pathViewController = 'pages.backend.category.';
    protected $recursiveCategoriesArray = [];
    protected $categoryService;
    protected $categoryPrefix;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->categoryPrefix = config('core.routes.category.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {

        $category = $this->categoryService->getAll($request);
        $groupByStatus = $this->categoryService->groupByStatus()->toArray();
        return view($this->pathViewController . 'index', [
            'category' => $category,
            'groupByStatus' => $groupByStatus
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $parentCategories = $this->categoryService->getParentCategories(null, ['task' => 'category-create']);
        $recursiveCategories = $this->convertCategories($parentCategories);
        return view($this->pathViewController . 'create', [
            'recursiveCategories' => $recursiveCategories
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->categoryService->store($request);
        return redirect()->route($this->adminPrefix . "." . $this->categoryPrefix . ".index")->with('success',' Added Category');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $parentCategories = $this->categoryService->getParentCategories($id, ['task' => 'category-edit']);
        $recursiveCategories = $this->convertCategories($parentCategories);
        $categoryStatus = CategoryStatusEnum::getValues();
        $data = Category::with(['children'])->findOrFail($id);

        // return view
        return view($this->pathViewController . 'edit', [
            'data' => collect($data),
            'recursiveCategories' => $recursiveCategories,
            'categoryStatus'      => $categoryStatus
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $this->categoryService->update($request, $id);
        return redirect()->route($this->adminPrefix . "." . $this->categoryPrefix . ".edit", ['category' => $id])->with('success', 'Cập nhật thành công');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);
        return redirect()->route($this->adminPrefix . "." . $this->categoryPrefix . ".index")->with('success',' Delete thành công');
    }

    /**
     * Recursive categories
     * @param $data
     * @param string $str
     * @return array|void
     */
    private function convertCategories($data, $str = "", $concat = '--') {
        $substring = $concat . $str;
        if (count($data) === 0) {
            return;
        }
        for ( $i = 0; $i < count($data); $i++) {
            $this->recursiveCategoriesArray[$data[$i]['id']] = ['name' => $str . $data[$i]['name']];
            $this->convertCategories($data[$i]['children'], $substring);
        }
        return $this->recursiveCategoriesArray;
    }
}
