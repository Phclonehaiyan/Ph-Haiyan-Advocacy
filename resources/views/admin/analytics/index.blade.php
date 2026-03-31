@extends('admin.layouts.app', ['pageTitle' => 'Analytics'])

@section('content')
    <section class="flex flex-col gap-4 rounded-[30px] border border-white/80 bg-white/90 p-6 shadow-soft backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="admin-kicker">Visitor Intelligence</div>
            <h2 class="admin-heading mt-2">Monitor traffic, top pages, referrers, and current visitors.</h2>
            <p class="admin-copy mt-3">Analytics uses first-party pageview tracking, filters likely bots, and ignores duplicate reloads on the same page within 30 seconds.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @foreach ($periodOptions as $period)
                <a
                    href="{{ route('admin.analytics.index', ['days' => $period]) }}"
                    class="{{ $selectedDays === $period ? 'btn-primary' : 'btn-secondary' }} !px-4 !py-2"
                >
                    {{ $period }} days
                </a>
            @endforeach

            <a href="{{ route('admin.analytics.export', ['days' => $selectedDays]) }}" class="btn-secondary !px-4 !py-2">
                <x-icon name="download" class="h-4 w-4" />
                Export CSV
            </a>
        </div>
    </section>

    <section class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-5">
        @foreach ($analyticsStats as $stat)
            <div class="admin-panel">
                <div class="admin-kicker">Analytics</div>
                <div class="mt-2 text-sm font-medium text-slate-500">{{ $stat['label'] }}</div>
                <div class="mt-4 text-4xl font-semibold tracking-tight text-pine-950">{{ number_format($stat['value']) }}</div>
            </div>
        @endforeach
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
        <div class="admin-panel">
            <div class="admin-kicker">Traffic Trend</div>
            <h2 class="admin-heading mt-2">Views and visitors across the latest {{ count($analyticsTrend) }} days.</h2>
            <div class="mt-6 space-y-4">
                @foreach ($analyticsTrend as $day)
                    <div class="grid gap-3 sm:grid-cols-[72px_minmax(0,1fr)_auto] sm:items-center">
                        <div class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $day['label'] }}</div>
                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-[linear-gradient(90deg,_#0f3d2e,_#17808f)]" style="width: {{ max(4, (int) round(($day['views'] / $maxTrendViews) * 100)) }}%;"></div>
                        </div>
                        <div class="text-sm text-slate-500">
                            <span class="font-semibold text-pine-950">{{ number_format($day['views']) }}</span> views
                            <span class="mx-2 text-slate-300">/</span>
                            <span>{{ number_format($day['visitors']) }} visitors</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-kicker">Real-Time</div>
            <h2 class="admin-heading mt-2">Visitors active within the last 5 minutes.</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">This panel refreshes automatically every 60 seconds.</p>
            <div class="mt-5 space-y-4">
                @forelse ($activeVisitors as $visitor)
                    <div class="rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="font-medium text-pine-950">{{ $visitor->page_label ?: $visitor->path }}</div>
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-400">{{ $visitor->device_type }}</div>
                        </div>
                        <div class="mt-1 text-sm text-slate-600">{{ $visitor->path }}</div>
                        <div class="mt-2 text-xs text-slate-400">{{ $visitor->viewed_at?->diffForHumans() }} · {{ $visitor->referrer_host ?: 'Direct / Unknown' }}</div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No visitors active in the last 5 minutes.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <div class="admin-panel">
            <div class="admin-kicker">Most Visited Pages</div>
            <h2 class="admin-heading mt-2">Top pages for the selected period.</h2>
            <div class="mt-5 space-y-4">
                @forelse ($topPages as $pageView)
                    <div class="rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="font-medium text-pine-950">{{ $pageView->page_label ?: $pageView->path }}</div>
                            <div class="text-sm font-semibold text-pine-900">{{ number_format($pageView->views) }} views</div>
                        </div>
                        <div class="mt-1 text-sm text-slate-600">{{ $pageView->path }}</div>
                        <div class="mt-2 text-xs text-slate-400">{{ number_format($pageView->visitors) }} unique visitors</div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">Traffic data will appear here after visitors browse the public site.</div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-panel">
                <div class="admin-kicker">Devices</div>
                <div class="mt-5 space-y-3">
                    @forelse ($deviceBreakdown as $device)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4 text-sm">
                            <span class="font-medium text-pine-950">{{ $device->device_type ?: 'Unknown' }}</span>
                            <span class="text-slate-500">{{ number_format($device->views) }} views · {{ number_format($device->visitors) }} visitors</span>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No device analytics yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-kicker">Top Referrers</div>
                <div class="mt-5 space-y-3">
                    @forelse ($referrerBreakdown as $referrer)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4 text-sm">
                            <span class="font-medium text-pine-950">{{ $referrer->referrer_host }}</span>
                            <span class="text-slate-500">{{ number_format($referrer->visits) }} visits · {{ number_format($referrer->visitors) }} visitors</span>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No referral data yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <script>
        setTimeout(() => window.location.reload(), 60000);
    </script>
@endsection
