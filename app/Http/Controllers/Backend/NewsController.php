<?php

namespace App\Http\Controllers\Backend;
use App\Enums\NewsStatusEnum;
use App\Models\News;
use App\Traits\LogActivitiesAdm;
use App\Http\Requests\Backend\News\CreateNewsRequest;
use App\Http\Requests\Backend\News\UpdateNewsRequest;
use App\Services\Backend\NewsService;
use App\Services\Backend\CategoryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends BaseController
{
    use LogActivitiesAdm;

    private $pathViewController = 'pages.backend.news.';
    protected $recursiveCategoriesArray = [];
    protected $newsService;
    protected $categoryService;
    protected $newsPrefix;

    public function __construct(NewsService $newsService, CategoryService $categoryService)
    {
        parent::__construct();
        $this->newsService = $newsService;
        $this->categoryService = $categoryService;
        $this->newsPrefix = config('core.routes.news.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $news = $this->newsService->getAll($request);
        $groupByStatus = $this->newsService->groupByStatus()->toArray();
        return view($this->pathViewController . 'index', [
            'news' => $news,
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
     * @param CreateNewsRequest $request
     * @return Response
     */
    public function store(CreateNewsRequest $request)
    {
        $this->newsService->store($request);
        $this->createLog('Add news', $request, 'Create news from admin panel');
        return redirect()->route($this->adminPrefix . "." . $this->newsPrefix . ".index")
                         ->with('success',' Added News');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $newsStatus = NewsStatusEnum::getValues();
        $parentCategories = $this->categoryService->getParentCategories(null, ['task' => 'category-create']);
        $recursiveCategories = $this->convertCategories($parentCategories);
        $data = News::with('category')->findOrFail($id);
        // return view
        return view('pages.backend.news.edit', [
            'data' => collect($data),
            'recursiveCategories' => $recursiveCategories,
            'newsStatus' => $newsStatus
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        $data = $this->newsService->update($request, $id);
        return redirect()->route($this->adminPrefix . "." . $this->newsPrefix . ".edit", ['news' => $id])
                         ->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param News $news
     * @return RedirectResponse
     */
    public function destroy(News $news)
    {
        $this->newsService->delete($news);
        return redirect()->route($this->adminPrefix . "." . $this->newsPrefix . ".index")
                         ->with('success',' Delete thành công');
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
