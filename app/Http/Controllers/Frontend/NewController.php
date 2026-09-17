<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;

class NewController extends BaseController
{
    public function index()
    {
        // Get featured news (latest published)
        $featured = News::with('category')
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->first();

        // Map category slugs to filter values
        $categoryMap = [
            'su-kien' => 'event',
            'thanh-tich' => 'achievement',
            'tin-tuc' => 'news',
            'thong-bao' => 'announcement',
        ];

        // Get news by category
        $newsByCategory = [];
        
        // All news
        $newsByCategory['all'] = News::with('category')
            ->where('is_published', true)
            ->when($featured, function ($query) use ($featured) {
                return $query->where('id', '!=', $featured->id);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        // News by category slug
        foreach ($categoryMap as $slug => $filterKey) {
            $category = Category::where('slug', $slug)->where('status', 'active')->first();
            if ($category) {
                $newsByCategory[$filterKey] = News::with('category')
                    ->where('is_published', true)
                    ->where('cate_id', $category->id)
                    ->when($featured, function ($query) use ($featured) {
                        return $query->where('id', '!=', $featured->id);
                    })
                    ->orderByDesc('published_at')
                    ->orderByDesc('id')
                    ->take(8)
                    ->get();
            } else {
                $newsByCategory[$filterKey] = collect([]);
            }
        }

        return view('pages.frontend.new', [
            'userPrefix' => $this->userPrefix,
            'featured' => $featured,
            'newsByCategory' => $newsByCategory,
        ]);
    }
}
