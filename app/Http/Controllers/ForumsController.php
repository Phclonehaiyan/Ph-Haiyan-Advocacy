<?php

namespace App\Http\Controllers;

use App\Models\ForumTopic;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class ForumsController extends Controller
{
    public function index(): View
    {
        $topics = ForumTopic::query()->latest('last_activity_at')->get();
        $leadStory = $topics->firstWhere('slug', 'rationale-of-the-forum');

        return view('forums.index', [
            'page' => Page::published()->where('slug', 'forums')->firstOrFail(),
            'topics' => $topics,
            'leadStory' => $leadStory,
            'featuredTopics' => $topics
                ->where('is_featured', true)
                ->when($leadStory, fn ($collection) => $collection->where('slug', '!=', $leadStory->slug))
                ->take(3)
                ->values(),
            'recentTopics' => $topics->take(8),
            'categories' => $topics->pluck('category')->unique()->values(),
        ]);
    }
}
