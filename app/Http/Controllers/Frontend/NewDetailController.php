<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewDetailController extends BaseController
{
    public function index(Request $request, $id = null)
    {
        $newsId = $id ?: $request->query('id');

        $news = News::with('category')
            ->where('is_published', true)
            ->findOrFail($newsId);

        $relatedNews = News::with('category')
            ->where('is_published', true)
            ->where('id', '!=', $news->id)
            ->when(!empty($news->cate_id), function ($query) use ($news) {
                return $query->where('cate_id', $news->cate_id);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $categoryStats = Category::query()
            ->where('status', 'active')
            ->whereIn('slug', ['su-kien', 'thanh-tich', 'tin-tuc', 'thong-bao'])
            ->withCount(['news' => function ($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('id')
            ->get();

        $suggestedKeywords = $this->extractSuggestedKeywords($news);

        return view('pages.frontend.new-detail', [
            'userPrefix' => $this->userPrefix,
            'news' => $news,
            'relatedNews' => $relatedNews,
            'categoryStats' => $categoryStats,
            'suggestedKeywords' => $suggestedKeywords,
        ]);
    }

    private function extractSuggestedKeywords(News $news): array
    {
        $rawText = trim($news->title . ' ' . strip_tags((string) $news->content));
        $normalized = Str::of($rawText)->lower()->ascii()->replaceMatches('/[^a-z0-9\s]/', ' ')->value();
        $tokens = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY);

        $stopWords = [
            'va', 'voi', 'cho', 'cua', 'cac', 'nhung', 'duoc', 'trong', 'theo', 'khi', 'tai', 'tu', 'den',
            'mot', 'nhieu', 'dang', 'se', 'da', 'nay', 'do', 'la', 'co', 'khong', 'sau', 'tren', 'duoi',
            'quan', 'ly', 'clb', 'thong', 'bao', 'tin', 'tuc'
        ];

        $frequency = [];
        foreach ($tokens as $token) {
            if (strlen($token) < 3 || in_array($token, $stopWords, true)) {
                continue;
            }
            $frequency[$token] = ($frequency[$token] ?? 0) + 1;
        }

        arsort($frequency);
        $keywords = array_slice(array_keys($frequency), 0, 8);

        return array_map(function ($keyword) {
            return Str::title($keyword);
        }, $keywords);
    }
}
