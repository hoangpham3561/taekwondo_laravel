<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CauLacBo;
use App\Models\KhoaHoc;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends BaseController
{
    /**
     * Trang chủ
     */
    public function index(Request $request)
    {
        // Set session để biết đang ở landing pages
        session(['order_type' => 'landing']);

        $club = CauLacBo::query()->latest('id')->first();
        $heroImageUrl = asset('client/images/nodata.png');
        if ($club && !empty($club->logo_url)) {
            $heroImageUrl = Str::startsWith($club->logo_url, ['http://', 'https://'])
                ? $club->logo_url
                : asset(ltrim($club->logo_url, '/'));
        }

        $featuredCourses = KhoaHoc::query()
            ->where('is_active', true)
            ->latest('id')
            ->take(3)
            ->get()
            ->map(function (KhoaHoc $course) {
                $imageUrl = $course->image_url;
                if (empty($imageUrl)) {
                    $course->resolved_image_url = asset('client/images/nodata.png');
                } elseif (Str::startsWith($imageUrl, ['http://', 'https://'])) {
                    $course->resolved_image_url = $imageUrl;
                } else {
                    $course->resolved_image_url = asset(ltrim($imageUrl, '/'));
                }

                return $course;
            });

        $latestNews = News::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take(3)
            ->get()
            ->map(function (News $news) {
                $imageUrl = $news->featured_image_url ?: $news->images;
                if (empty($imageUrl)) {
                    $news->resolved_image_url = asset('client/images/nodata.png');
                } elseif (Str::startsWith($imageUrl, ['http://', 'https://'])) {
                    $news->resolved_image_url = $imageUrl;
                } else {
                    $news->resolved_image_url = asset(ltrim($imageUrl, '/'));
                }

                return $news;
            });

        // Truyền data ra view
        return view('pages.frontend.index', [
            'userPrefix' => $this->userPrefix,
            'club' => $club,
            'heroImageUrl' => $heroImageUrl,
            'featuredCourses' => $featuredCourses,
            'latestNews' => $latestNews,
        ]);
    }
}
