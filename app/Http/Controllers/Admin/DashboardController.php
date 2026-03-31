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
use App\Models\PageView;
use App\Models\Project;
use App\Models\Video;
use App\Support\AdminResourceRegistry;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $analyticsWindow = now()->subDays(30);
        $today = CarbonImmutable::today();
        $trendStart = $today->subDays(13);
        $activeThreshold = now()->subMinutes(5);
        $hasAnalyticsTable = Schema::hasTable('page_views');

        $stats = [
            ['label' => 'Pages', 'value' => Page::count()],
            ['label' => 'Projects', 'value' => Project::count()],
            ['label' => 'News', 'value' => NewsPost::count()],
            ['label' => 'Letters', 'value' => Letter::count()],
            ['label' => 'Gallery', 'value' => GalleryItem::count()],
            ['label' => 'Messages', 'value' => ContactMessage::count()],
            ['label' => 'Unread Messages', 'value' => ContactMessage::where('status', 'new')->count()],
        ];

        $analyticsStats = $hasAnalyticsTable ? [
            ['label' => 'Page Views (30d)', 'value' => PageView::query()->where('viewed_at', '>=', $analyticsWindow)->count()],
            ['label' => 'Unique Visitors (30d)', 'value' => PageView::query()->where('viewed_at', '>=', $analyticsWindow)->distinct('visitor_key')->count('visitor_key')],
            ['label' => 'Views Today', 'value' => PageView::query()->where('viewed_at', '>=', $today)->count()],
            ['label' => 'Active Now', 'value' => PageView::query()->where('viewed_at', '>=', $activeThreshold)->distinct('visitor_key')->count('visitor_key')],
        ] : [
            ['label' => 'Page Views (30d)', 'value' => 0],
            ['label' => 'Unique Visitors (30d)', 'value' => 0],
            ['label' => 'Views Today', 'value' => 0],
            ['label' => 'Active Now', 'value' => 0],
        ];

        $trendRows = $hasAnalyticsTable
            ? PageView::query()
                ->selectRaw('DATE(viewed_at) as view_date, COUNT(*) as views, COUNT(DISTINCT visitor_key) as visitors')
                ->where('viewed_at', '>=', $trendStart->startOfDay())
                ->groupBy('view_date')
                ->orderBy('view_date')
                ->get()
                ->keyBy('view_date')
            : collect();

        $analyticsTrend = collect(range(0, 13))
            ->map(function (int $offset) use ($trendStart, $trendRows): array {
                $date = $trendStart->addDays($offset)->toDateString();
                $row = $trendRows->get($date);

                return [
                    'date' => $date,
                    'label' => CarbonImmutable::parse($date)->format('M d'),
                    'views' => (int) ($row->views ?? 0),
                    'visitors' => (int) ($row->visitors ?? 0),
                ];
            });

        $maxTrendViews = max(1, $analyticsTrend->max('views'));

        $topPages = $hasAnalyticsTable
            ? PageView::query()
                ->select('path', 'page_label')
                ->selectRaw('COUNT(*) as views')
                ->selectRaw('COUNT(DISTINCT visitor_key) as visitors')
                ->where('viewed_at', '>=', $analyticsWindow)
                ->groupBy('path', 'page_label')
                ->orderByDesc('views')
                ->limit(8)
                ->get()
            : collect();

        $deviceBreakdown = $hasAnalyticsTable
            ? PageView::query()
                ->select('device_type')
                ->selectRaw('COUNT(*) as views')
                ->where('viewed_at', '>=', $analyticsWindow)
                ->groupBy('device_type')
                ->orderByDesc('views')
                ->get()
            : collect();

        $referrerBreakdown = $hasAnalyticsTable
            ? PageView::query()
                ->select('referrer_host')
                ->selectRaw('COUNT(*) as visits')
                ->where('viewed_at', '>=', $analyticsWindow)
                ->whereNotNull('referrer_host')
                ->groupBy('referrer_host')
                ->orderByDesc('visits')
                ->limit(6)
                ->get()
            : collect();

        $activeVisitors = collect();

        if ($hasAnalyticsTable) {
            $activeVisitorSubquery = PageView::query()
                ->select('visitor_key', DB::raw('MAX(viewed_at) as last_viewed_at'))
                ->where('viewed_at', '>=', $activeThreshold)
                ->groupBy('visitor_key');

            $activeVisitors = PageView::query()
                ->joinSub($activeVisitorSubquery, 'active_visitors', function ($join): void {
                    $join->on('page_views.visitor_key', '=', 'active_visitors.visitor_key')
                        ->on('page_views.viewed_at', '=', 'active_visitors.last_viewed_at');
                })
                ->orderByDesc('page_views.viewed_at')
                ->limit(8)
                ->get([
                    'page_views.visitor_key',
                    'page_views.page_label',
                    'page_views.path',
                    'page_views.device_type',
                    'page_views.referrer_host',
                    'page_views.viewed_at',
                ]);
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'analyticsStats' => $analyticsStats,
            'analyticsTrend' => $analyticsTrend,
            'maxTrendViews' => $maxTrendViews,
            'topPages' => $topPages,
            'deviceBreakdown' => $deviceBreakdown,
            'referrerBreakdown' => $referrerBreakdown,
            'activeVisitors' => $activeVisitors,
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
