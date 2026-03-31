<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $posts = NewsPost::published()->latest('published_at')->get();
        $categories = $posts->pluck('category')->filter()->unique()->values();
        $activeCategory = trim((string) $request->query('category', 'all'));

        if ($activeCategory !== 'all' && ! $categories->contains($activeCategory)) {
            $activeCategory = 'all';
        }

        $filteredPosts = $activeCategory === 'all'
            ? $posts->values()
            : $posts->where('category', $activeCategory)->values();

        $featuredPost = $filteredPosts->firstWhere('is_featured', true) ?? $filteredPosts->first();
        $archivePosts = $filteredPosts
            ->when($featuredPost, fn (Collection $collection) => $collection->where('id', '!=', $featuredPost->id))
            ->values();
        $archiveGroups = $archivePosts->groupBy(fn (NewsPost $post) => $post->published_at?->format('Y') ?? 'Archive');

        return view('news.index', [
            'page' => Page::published()->where('slug', 'news')->firstOrFail(),
            'featuredPost' => $featuredPost,
            'archivePosts' => $archivePosts,
            'archiveGroups' => $archiveGroups,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'totalPosts' => $filteredPosts->count(),
        ]);
    }

    public function show(NewsPost $newsPost): View
    {
        abort_unless(
            $newsPost->is_published && ($newsPost->published_at === null || $newsPost->published_at->lte(now())),
            404
        );

        $relatedPosts = NewsPost::published()
            ->whereKeyNot($newsPost->getKey())
            ->get()
            ->sortByDesc(fn (NewsPost $candidate) => $this->relatedScore($newsPost, $candidate))
            ->take(3)
            ->values();

        return view('news.show', [
            'page' => Page::published()->where('slug', 'news')->firstOrFail(),
            'post' => $newsPost,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    private function relatedScore(NewsPost $current, NewsPost $candidate): int
    {
        $score = 0;

        if ($candidate->category === $current->category) {
            $score += 60;
        }

        if ($candidate->is_featured) {
            $score += 8;
        }

        if ($candidate->published_at && $current->published_at) {
            $dayDiff = abs($candidate->published_at->diffInDays($current->published_at));
            $score += max(0, 20 - min($dayDiff, 20));
        }

        return $score + ($this->sharedTokenCount($current, $candidate) * 7);
    }

    private function sharedTokenCount(NewsPost $current, NewsPost $candidate): int
    {
        $tokenize = static function (NewsPost $post): Collection {
            $text = Str::of(implode(' ', array_filter([
                $post->title,
                $post->category,
                $post->excerpt,
            ])))->lower();

            return collect(preg_split('/[^a-z0-9]+/i', (string) $text, -1, PREG_SPLIT_NO_EMPTY))
                ->filter(fn (string $token) => strlen($token) > 3)
                ->unique()
                ->values();
        };

        return $tokenize($current)->intersect($tokenize($candidate))->count();
    }
}
