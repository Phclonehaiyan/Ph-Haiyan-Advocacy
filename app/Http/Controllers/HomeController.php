<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Event;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Project;
use App\Models\Video;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $page = Page::published()->where('slug', 'home')->firstOrFail();
        $aboutPage = Page::published()->where('slug', 'about-ph-haiyan')->first();
        $whatWeDoPage = Page::published()->where('slug', 'what-we-do')->first();

        $featuredNews = NewsPost::published()
            ->featured()
            ->latest('published_at')
            ->first() ?? NewsPost::published()->latest('published_at')->first();

        return view('home.index', [
            'page' => $page,
            'aboutPage' => $aboutPage,
            'whatWeDoPage' => $whatWeDoPage,
            'featuredProjects' => Project::query()
                ->ordered()
                ->take(4)
                ->get(),
            'featuredVideos' => Video::published()
                ->orderByDesc('is_featured')
                ->latest('published_at')
                ->take(6)
                ->get(),
            'featuredNews' => $featuredNews,
            'supportingNews' => NewsPost::published()
                ->latest('published_at')
                ->when($featuredNews, fn ($query) => $query->whereKeyNot($featuredNews->getKey()))
                ->take(3)
                ->get(),
            'archiveHighlights' => Event::published()
                ->featured()
                ->latest('start_at')
                ->take(4)
                ->get(),
            'recentActivities' => Activity::query()
                ->latest('activity_date')
                ->take(4)
                ->get(),
        ]);
    }
}
