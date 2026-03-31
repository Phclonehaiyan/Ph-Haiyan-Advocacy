<?php

namespace App\Support\Analytics;

use App\Models\PageView;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyticsReport
{
    public function available(): bool
    {
        return Schema::hasTable('page_views');
    }

    public function build(int $days = 30): array
    {
        $days = in_array($days, [7, 30, 90], true) ? $days : 30;
        $windowStart = now()->subDays($days);
        $activeThreshold = now()->subMinutes(5);
        $today = CarbonImmutable::today();
        $trendStart = $today->subDays(min($days, 14) - 1);

        if (! $this->available()) {
            return $this->empty($days, $trendStart);
        }

        $pageViewsQuery = PageView::query()->where('viewed_at', '>=', $windowStart);

        $analyticsStats = [
            ['label' => "Page Views ({$days}d)", 'value' => (clone $pageViewsQuery)->count()],
            ['label' => "Unique Visitors ({$days}d)", 'value' => (clone $pageViewsQuery)->distinct('visitor_key')->count('visitor_key')],
            ['label' => "Sessions ({$days}d)", 'value' => (clone $pageViewsQuery)->whereNotNull('session_id')->distinct('session_id')->count('session_id')],
            ['label' => 'Views Today', 'value' => PageView::query()->where('viewed_at', '>=', $today)->count()],
            ['label' => 'Active Now', 'value' => PageView::query()->where('viewed_at', '>=', $activeThreshold)->distinct('visitor_key')->count('visitor_key')],
        ];

        $trendRows = PageView::query()
            ->selectRaw('DATE(viewed_at) as view_date, COUNT(*) as views, COUNT(DISTINCT visitor_key) as visitors')
            ->where('viewed_at', '>=', $trendStart->startOfDay())
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->get()
            ->keyBy('view_date');

        $analyticsTrend = collect(range(0, min($days, 14) - 1))
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

        $topPages = PageView::query()
            ->select('path', 'page_label')
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT visitor_key) as visitors')
            ->where('viewed_at', '>=', $windowStart)
            ->groupBy('path', 'page_label')
            ->orderByDesc('views')
            ->limit(12)
            ->get();

        $deviceBreakdown = PageView::query()
            ->select('device_type')
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT visitor_key) as visitors')
            ->where('viewed_at', '>=', $windowStart)
            ->groupBy('device_type')
            ->orderByDesc('views')
            ->get();

        $referrerBreakdown = PageView::query()
            ->select('referrer_host')
            ->selectRaw('COUNT(*) as visits')
            ->selectRaw('COUNT(DISTINCT visitor_key) as visitors')
            ->where('viewed_at', '>=', $windowStart)
            ->whereNotNull('referrer_host')
            ->groupBy('referrer_host')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

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
            ->limit(12)
            ->get([
                'page_views.visitor_key',
                'page_views.page_label',
                'page_views.path',
                'page_views.device_type',
                'page_views.referrer_host',
                'page_views.viewed_at',
            ]);

        return [
            'days' => $days,
            'windowStart' => $windowStart,
            'analyticsStats' => $analyticsStats,
            'analyticsTrend' => $analyticsTrend,
            'maxTrendViews' => max(1, $analyticsTrend->max('views')),
            'topPages' => $topPages,
            'deviceBreakdown' => $deviceBreakdown,
            'referrerBreakdown' => $referrerBreakdown,
            'activeVisitors' => $activeVisitors,
        ];
    }

    public function exportRows(int $days = 30): Collection
    {
        if (! $this->available()) {
            return collect();
        }

        $days = in_array($days, [7, 30, 90], true) ? $days : 30;
        $windowStart = now()->subDays($days);

        return PageView::query()
            ->select('path', 'page_label', 'device_type', 'referrer_host')
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT visitor_key) as visitors')
            ->where('viewed_at', '>=', $windowStart)
            ->groupBy('path', 'page_label', 'device_type', 'referrer_host')
            ->orderByDesc('views')
            ->get();
    }

    private function empty(int $days, CarbonImmutable $trendStart): array
    {
        $trend = collect(range(0, min($days, 14) - 1))
            ->map(fn (int $offset) => [
                'date' => $trendStart->addDays($offset)->toDateString(),
                'label' => $trendStart->addDays($offset)->format('M d'),
                'views' => 0,
                'visitors' => 0,
            ]);

        return [
            'days' => $days,
            'windowStart' => now()->subDays($days),
            'analyticsStats' => [
                ['label' => "Page Views ({$days}d)", 'value' => 0],
                ['label' => "Unique Visitors ({$days}d)", 'value' => 0],
                ['label' => "Sessions ({$days}d)", 'value' => 0],
                ['label' => 'Views Today', 'value' => 0],
                ['label' => 'Active Now', 'value' => 0],
            ],
            'analyticsTrend' => $trend,
            'maxTrendViews' => 1,
            'topPages' => collect(),
            'deviceBreakdown' => collect(),
            'referrerBreakdown' => collect(),
            'activeVisitors' => collect(),
        ];
    }
}
