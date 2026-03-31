<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\ForumTopic;
use App\Models\GalleryItem;
use App\Models\Letter;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Project;
use App\Models\Video;
use App\Support\Analytics\AnalyticsReport;
use App\Support\AdminResourceRegistry;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(AnalyticsReport $analyticsReport): View
    {
        $stats = [
            ['label' => 'Pages', 'value' => Page::count()],
            ['label' => 'Projects', 'value' => Project::count()],
            ['label' => 'News', 'value' => NewsPost::count()],
            ['label' => 'Letters', 'value' => Letter::count()],
            ['label' => 'Gallery', 'value' => GalleryItem::count()],
            ['label' => 'Messages', 'value' => ContactMessage::count()],
            ['label' => 'Unread Messages', 'value' => ContactMessage::where('status', 'new')->count()],
        ];

        $analytics = $analyticsReport->build(30);

        return view('admin.dashboard', [
            'stats' => $stats,
            'analyticsStats' => $analytics['analyticsStats'],
            'analyticsTrend' => $analytics['analyticsTrend'],
            'maxTrendViews' => $analytics['maxTrendViews'],
            'topPages' => collect($analytics['topPages'])->take(8),
            'deviceBreakdown' => collect($analytics['deviceBreakdown'])->take(3),
            'referrerBreakdown' => collect($analytics['referrerBreakdown'])->take(4),
            'activeVisitors' => collect($analytics['activeVisitors'])->take(6),
            'resources' => AdminResourceRegistry::all(),
            'recentMessages' => ContactMessage::query()->latest('submitted_at')->limit(5)->get(),
            'recentUpdates' => NewsPost::query()->latest('published_at')->limit(5)->get(),
            'activitySummary' => [
                'events' => Event::count(),
                'activities' => Activity::count(),
                'videos' => Video::count(),
                'forums' => ForumTopic::count(),
            ],
        ]);
    }
}
