<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\NewsPost;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response|View
    {
        $staticPages = collect([
            ['url' => route('home'), 'lastmod' => Page::published()->where('slug', 'home')->value('updated_at')],
            ['url' => route('about'), 'lastmod' => Page::published()->where('slug', 'about-ph-haiyan')->value('updated_at')],
            ['url' => route('what-we-do'), 'lastmod' => Page::published()->where('slug', 'what-we-do')->value('updated_at')],
            ['url' => route('projects.index'), 'lastmod' => Page::published()->where('slug', 'projects')->value('updated_at')],
            ['url' => route('gallery.index'), 'lastmod' => Page::published()->where('slug', 'gallery')->value('updated_at')],
            ['url' => route('forums.index'), 'lastmod' => Page::published()->where('slug', 'forums')->value('updated_at')],
            ['url' => route('letters.index'), 'lastmod' => Page::published()->where('slug', 'letters')->value('updated_at')],
            ['url' => route('news.index'), 'lastmod' => Page::published()->where('slug', 'news')->value('updated_at')],
            ['url' => route('events.index'), 'lastmod' => Page::published()->where('slug', 'events')->value('updated_at')],
            ['url' => route('contact.index'), 'lastmod' => Page::published()->where('slug', 'contact')->value('updated_at')],
            ['url' => route('support'), 'lastmod' => Page::published()->where('slug', 'support')->value('updated_at')],
        ]);

        $newsPages = NewsPost::published()
            ->get()
            ->map(fn (NewsPost $post) => [
                'url' => route('news.show', $post),
                'lastmod' => $post->updated_at ?? $post->published_at,
            ]);

        $letterPages = Letter::published()
            ->get()
            ->map(fn (Letter $letter) => [
                'url' => route('letters.show', $letter),
                'lastmod' => $letter->updated_at ?? $letter->published_at,
            ]);

        return response()
            ->view('seo.sitemap', [
                'urls' => $staticPages
                    ->merge($newsPages)
                    ->merge($letterPages)
                    ->filter(fn (array $item) => filled($item['url']))
                    ->values(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
