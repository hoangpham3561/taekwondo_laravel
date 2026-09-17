<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Illuminate\Support\Str;

class ListCoffeeController extends BaseController
{
    public function index()
    {
        $courses = KhoaHoc::query()
            ->where('is_active', true)
            ->latest('id')
            ->get()
            ->map(function (KhoaHoc $course) {
                $course->resolved_image_url = $this->resolveCourseImageUrl($course->image_url);

                return $course;
            });

        return view('pages.frontend.course', [
            'userPrefix' => $this->userPrefix,
            'courses' => $courses,
        ]);
    }

    public function detail(int $id)
    {
        $course = KhoaHoc::query()
            ->with(['coach', 'club', 'branch'])
            ->where('is_active', true)
            ->findOrFail($id);

        $course->resolved_image_url = $this->resolveCourseImageUrl($course->image_url);

        return view('pages.frontend.course-detail', [
            'userPrefix' => $this->userPrefix,
            'course' => $course,
        ]);
    }

    protected function resolveCourseImageUrl(?string $imageUrl): string
    {
        if (empty($imageUrl)) {
            return asset('client/images/nodata.png');
        }

        if (Str::startsWith($imageUrl, ['http://', 'https://'])) {
            return $imageUrl;
        }

        return asset(ltrim($imageUrl, '/'));
    }
}
