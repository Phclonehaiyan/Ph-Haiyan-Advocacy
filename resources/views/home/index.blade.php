@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $content = $page->content ?? [];
        $aboutPreview = $content['about_preview'] ?? [];
        $impact = $content['impact_cta'] ?? [];
        $whatWeDoItems = data_get($whatWeDoPage, 'content.initiatives', []);
        $heroLines = $content['hero_title_lines'] ?? ['Building', 'Resilient', 'Communities'];
        $heroIntro = $content['hero_intro'] ?? config('site.organization.tagline');
        $heroImage = asset(ltrim($page->hero_image ?? '/images/hero/mangrove-hero.jpg', '/'));
        $heroTeaser = $content['hero_teaser'] ?? [
            'eyebrow' => 'Updates',
            'title' => 'Latest news and recent activity.',
            'actions' => [
                ['label' => 'News', 'href' => '#latest-updates'],
                ['label' => 'Activities', 'href' => '#recent-activities'],
            ],
        ];
        $activityCount = $recentActivities->count();
        $featuredActivityPreview = $recentActivities->first();
        $supportingActivityPreview = $recentActivities->slice(1)->values();
        $newsDigest = $supportingNews->take(3)->values();
        $activityDigest = $supportingActivityPreview->take(3)->values();
        $featuredArchiveEvent = $archiveHighlights->first();
        $supportingArchiveEvents = $archiveHighlights->slice(1)->values();
        $archiveReadMoreLink = $featuredArchiveEvent?->slug === 'voices-for-resilience-flood-control-and-mitigation-forum'
            ? route('forums.index').'#topic-rationale-of-the-forum'
            : route('events.index');
        $impactBackgroundPath = data_get($impact, 'background_image');
        $impactBackground = asset(ltrim(filled($impactBackgroundPath) ? $impactBackgroundPath : '/images/hero/mangrove-river-bright.jpg', '/'));
    @endphp

    <section class="relative isolate overflow-hidden bg-[#07130f]">
        <img src="{{ $heroImage }}" alt="Mangrove reforestation landscape in the Philippines" class="absolute inset-0 h-full w-full object-cover" style="object-position: center 50%;">
        <div class="absolute inset-0 bg-[linear-gradient(90deg,_rgba(7,19,15,0.62)_0%,_rgba(7,19,15,0.52)_18%,_rgba(7,19,15,0.2)_42%,_rgba(7,19,15,0.02)_100%)]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(180deg,_rgba(255,255,255,0.12)_0%,_rgba(7,19,15,0.02)_22%,_rgba(7,19,15,0.26)_100%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.12),_transparent_34%),radial-gradient(circle_at_center_right,_rgba(255,255,255,0.05),_transparent_46%),linear-gradient(90deg,_rgba(7,19,15,0)_34%,_rgba(7,19,15,0.05)_68%,_rgba(7,19,15,0.1)_100%)]"></div>
        <div class="absolute inset-x-0 bottom-0 h-44 bg-[linear-gradient(180deg,_rgba(7,19,15,0)_0%,_rgba(7,19,15,0.48)_100%)]"></div>

        <div class="section-shell relative min-h-[74vh] pt-16 pb-28 sm:pt-20 sm:pb-32 lg:min-h-[86vh] lg:pt-24 lg:pb-40">
            <div class="max-w-[700px] pt-2 lg:pt-4">
                <div class="text-xs font-semibold uppercase tracking-[0.34em] text-[#f0c74d] [text-shadow:0_4px_16px_rgba(0,0,0,0.32)] sm:text-sm lg:text-[0.95rem]">
                    {{ strtoupper($page->hero_eyebrow) }}
                </div>

                <h1 class="mt-6 max-w-[680px] font-display text-[clamp(3.2rem,6.6vw,6.4rem)] leading-[0.92] tracking-[-0.045em] text-white [text-shadow:0_16px_36px_rgba(0,0,0,0.22)]">
                    @foreach ($heroLines as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h1>

                <div class="mt-8 max-w-[620px] rounded-[28px] border border-white/18 bg-slate-950/54 px-6 py-5 text-[1.02rem] font-medium leading-8 text-white shadow-[0_18px_34px_rgba(0,0,0,0.18)] backdrop-blur-xl sm:px-8 sm:py-6">
                    {{ $heroIntro }}
                </div>

                @if (! empty($content['hero_chips']))
                    <div class="mt-8 grid grid-cols-[1fr_auto_1fr_auto_1fr] items-center gap-x-2 text-[0.72rem] font-semibold uppercase tracking-[0.24em] text-white [text-shadow:0_3px_12px_rgba(0,0,0,0.28)] sm:hidden">
                        @foreach ($content['hero_chips'] as $chip)
                            <span class="text-center">{{ strtoupper($chip) }}</span>
                            @if (! $loop->last)
                                <span class="text-center text-[#f0c74d]">&bull;</span>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-8 hidden flex-wrap items-center gap-x-5 gap-y-3 text-sm font-semibold uppercase tracking-[0.34em] text-white [text-shadow:0_3px_12px_rgba(0,0,0,0.28)] sm:flex sm:text-base">
                        @foreach ($content['hero_chips'] as $chip)
                            <span>{{ strtoupper($chip) }}</span>
                            @if (! $loop->last)
                                <span class="text-[#f0c74d]">&bull;</span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div id="latest-updates" x-data="{ tab: 'news' }" class="section-shell relative z-10 -mt-12 pb-6 sm:-mt-16 lg:-mt-20">
        <div class="mx-auto max-w-6xl overflow-hidden rounded-[36px] border border-white/80 bg-[linear-gradient(135deg,_rgba(255,255,255,0.97),_rgba(244,248,245,0.94))] shadow-[0_24px_70px_rgba(7,17,13,0.14)]">
            <div class="relative rounded-[35px] border border-white/60 px-6 py-6 backdrop-blur-xl sm:px-8 sm:py-8 lg:px-10 lg:py-10">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-[radial-gradient(circle_at_top_left,_rgba(16,118,135,0.12),_transparent_44%),radial-gradient(circle_at_top_right,_rgba(15,61,46,0.1),_transparent_40%)]"></div>

                <div class="relative grid gap-8 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-end">
                    <div class="max-w-3xl">
                        <div class="text-xs font-semibold uppercase tracking-[0.32em] text-pine-700 sm:text-sm">
                            {{ $heroTeaser['eyebrow'] }}
                        </div>
                        <h2 class="mt-3 font-display text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl lg:text-[2.7rem]">
                            {{ $heroTeaser['title'] }}
                        </h2>
                        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                            A curated preview of PH Haiyan's archived public updates and recent field work, carried over from the original website and organized for easier reading.
                        </p>

                    </div>

                    <div class="flex flex-col items-start gap-4 xl:items-end">
                        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white/88 p-1 shadow-soft">
                            @foreach ($heroTeaser['actions'] ?? [] as $action)
                                @php
                                    $tabName = \Illuminate\Support\Str::lower($action['label']);
                                @endphp
                                <button type="button"
                                    @click="tab = '{{ $tabName }}'"
                                    :class="tab === '{{ $tabName }}'
                                        ? 'bg-pine-950 text-white shadow-soft'
                                        : 'text-slate-600 hover:bg-pine-50 hover:text-pine-900'"
                                    class="inline-flex items-center justify-center rounded-full px-5 py-3 text-sm font-semibold transition">
                                    {{ $action['label'] }}
                                </button>
                            @endforeach
                        </div>

                    </div>
                </div>

                <div class="relative mt-8 border-t border-slate-200/80 pt-8">
                    <div x-cloak x-show="tab === 'news'" x-transition.opacity.duration.200ms class="grid gap-6 xl:grid-cols-[minmax(0,1.08fr)_minmax(320px,0.92fr)]">
                        @if ($featuredNews)
                            <article class="rounded-[30px] border border-slate-200/80 bg-white/96 p-6 shadow-soft sm:p-8">
                                <div class="grid gap-7 lg:grid-cols-[minmax(0,1fr)_200px] lg:items-start">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="chip">{{ $featuredNews->category }}</span>
                                            <span class="inline-flex items-center rounded-full bg-pine-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-pine-800">Featured Story</span>
                                            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $featuredNews->published_at?->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="mt-4 max-w-xl font-display text-[1.9rem] font-semibold tracking-tight text-slate-950 sm:text-[2.25rem] sm:leading-[1.04]">
                                            {{ $featuredNews->title }}
                                        </h3>
                                        <p class="mt-4 max-w-xl text-[1rem] leading-7 text-slate-600">
                                            {{ \Illuminate\Support\Str::limit($featuredNews->excerpt, 220) }}
                                        </p>

                                        <div class="mt-6 flex flex-wrap items-center gap-3">
                                            <a href="{{ route('news.show', $featuredNews) }}" class="btn-primary">
                                                Read full story
                                            </a>
                                        </div>
                                    </div>

                                    <div class="order-first lg:order-last">
                                        <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-slate-50 shadow-[0_18px_30px_rgba(15,23,42,0.08)]">
                                            <img src="{{ asset(ltrim($featuredNews->image, '/')) }}" alt="{{ $featuredNews->image_alt ?: $featuredNews->title }}" loading="lazy" decoding="async" class="aspect-[4/5] w-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endif

                        <aside class="grid content-start gap-4">
                            @foreach ($newsDigest as $post)
                                <a href="{{ route('news.show', $post) }}" class="group flex gap-4 rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-soft transition hover:-translate-y-0.5 hover:border-pine-200 hover:bg-pine-50/70">
                                    <img src="{{ asset(ltrim($post->image, '/')) }}" alt="{{ $post->image_alt ?: $post->title }}" loading="lazy" decoding="async" class="h-20 w-20 shrink-0 rounded-[18px] object-cover">
                                    <div class="min-w-0">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="chip">{{ $post->category }}</span>
                                            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $post->published_at?->format('M d') }}</span>
                                        </div>
                                        <h3 class="mt-3 text-base font-semibold leading-7 text-slate-950 transition group-hover:text-pine-900">
                                            {{ $post->title }}
                                        </h3>
                                        <p class="mt-1 text-sm leading-6 text-slate-600">
                                            {{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </aside>
                    </div>

                    <div x-cloak x-show="tab === 'activities'" x-transition.opacity.duration.200ms class="grid gap-6 xl:grid-cols-[minmax(0,1.08fr)_minmax(320px,0.92fr)]">
                        @if ($featuredActivityPreview)
                            <article class="overflow-hidden rounded-[30px] border border-slate-200/80 bg-white/90 shadow-soft">
                                <div class="grid gap-0 lg:grid-cols-[0.92fr_1.08fr]">
                                    <div class="overflow-hidden">
                                        <img src="{{ asset(ltrim($featuredActivityPreview->image, '/')) }}" alt="{{ $featuredActivityPreview->image_alt ?: $featuredActivityPreview->title }}" loading="lazy" decoding="async" class="h-full min-h-72 w-full object-cover">
                                    </div>
                                    <div class="p-6 sm:p-7 lg:p-8">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="chip">{{ $featuredActivityPreview->category }}</span>
                                            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $featuredActivityPreview->activity_date?->format('M d, Y') }}</span>
                                        </div>
                                        <div class="mt-6 text-xs font-semibold uppercase tracking-[0.28em] text-pine-700">
                                            Featured Activity
                                        </div>
                                        <h3 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950 sm:text-[2rem]">
                                            {{ $featuredActivityPreview->title }}
                                        </h3>
                                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                                            {{ \Illuminate\Support\Str::limit($featuredActivityPreview->summary, 210) }}
                                        </p>
                                        @if (data_get($featuredActivityPreview, 'content.highlights'))
                                            <div class="mt-6 space-y-3">
                                                @foreach (collect(data_get($featuredActivityPreview, 'content.highlights', []))->take(2) as $highlight)
                                                    <div class="flex items-start gap-3 text-sm leading-7 text-slate-600">
                                                        <span class="mt-2 h-2 w-2 rounded-full bg-pine-700"></span>
                                                        <span>{{ $highlight }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if ($featuredActivityPreview->location)
                                            <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-pine-100 bg-pine-50 px-4 py-2 text-sm font-medium text-pine-900">
                                                <x-icon name="map-pin" class="h-4 w-4 text-pine-700" />
                                                <span>{{ $featuredActivityPreview->location }}</span>
                                            </div>
                                        @endif
                                        <a href="{{ route('events.index') }}" class="btn-secondary mt-7">
                                            Open events and archives
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endif

                        <aside class="grid gap-4">
                            <div class="rounded-[28px] border border-slate-200/80 bg-[linear-gradient(135deg,_rgba(241,248,244,0.96),_rgba(255,255,255,0.92))] p-5 shadow-soft sm:p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-[0.28em] text-pine-700">Field Log</div>
                                        <div class="mt-2 text-lg font-semibold text-slate-950">Recent activity record</div>
                                    </div>
                                    <span class="rounded-full border border-pine-100 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-pine-800">
                                        {{ $activityCount }} entries
                                    </span>
                                </div>
                                <p class="mt-4 text-sm leading-7 text-slate-600">
                                    Coordination meetings, site inspections, and resilience forums are grouped here as a fast read before the full archive below.
                                </p>
                                <a href="{{ route('events.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-pine-800 transition hover:text-pine-950">
                                    Browse the events archive
                                    <x-icon name="arrow-up-right" class="h-4 w-4" />
                                </a>
                            </div>

                            @foreach ($activityDigest as $activity)
                                <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-soft">
                                    <div class="flex gap-4">
                                        <img src="{{ asset(ltrim($activity->image, '/')) }}" alt="{{ $activity->image_alt ?: $activity->title }}" loading="lazy" decoding="async" class="h-20 w-20 shrink-0 rounded-[18px] object-cover">
                                        <div class="min-w-0">
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="chip">{{ $activity->category }}</span>
                                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $activity->activity_date?->format('M d') }}</span>
                                            </div>
                                            <h3 class="mt-3 text-base font-semibold leading-7 text-slate-950">
                                                {{ $activity->title }}
                                            </h3>
                                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                                {{ \Illuminate\Support\Str::limit($activity->summary, 120) }}
                                            </p>
                                            @if ($activity->location)
                                                <div class="mt-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                    {{ $activity->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-shell py-12 lg:py-16">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <x-section-header
                eyebrow="Video Stories"
                title="Short-form public stories from PH Haiyan's Facebook page."
                description="These videos pull the advocacy into a more immediate format, with Facebook thumbnails and public view counts preserved as a snapshot for this site."
            />

            <a href="https://www.facebook.com/phhaiyanadvocacy/videos" target="_blank" rel="noreferrer" class="btn-secondary">
                Open Facebook videos
            </a>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($featuredVideos as $video)
                <x-cards.video :video="$video" />
            @endforeach
        </div>
    </section>

    <section class="section-shell py-8 lg:py-12">
        <x-section-header
            eyebrow="Mission Pillars"
            title="Three ways PH Haiyan moves from conviction to action."
            description="The organization's work stays anchored in long-view planning, community dialogue, and practical environmental action."
            align="center"
        />

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach ($content['mission_pillars'] ?? [] as $pillar)
                <x-cards.pillar :icon="$pillar['icon']" :title="$pillar['title']" :description="$pillar['description']" />
            @endforeach
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="grid items-center gap-10 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="image-frame">
                <img src="{{ asset(ltrim($aboutPreview['image'] ?? '/images/scenes/about-together.svg', '/')) }}" alt="Community empowerment and environmental advocacy activity" loading="lazy" decoding="async" class="h-full w-full object-cover">
            </div>

            <div>
                <x-section-header
                    eyebrow="About PH Haiyan"
                    :title="$aboutPreview['heading'] ?? 'Grounded in community. Guided by resilience.'"
                    :description="$aboutPage?->subtitle"
                />

                <div class="mt-6 space-y-5 text-base leading-8 text-slate-600">
                    @foreach ($aboutPreview['body'] ?? [] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <div class="mt-8">
                    <a href="{{ route('about') }}" class="btn-primary">
                        Read More about PH Haiyan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="What We Do"
            title="Work grounded in climate resilience, environmental protection, and community empowerment."
            description="From ecological restoration and disaster preparedness to policy advocacy and youth engagement, PH Haiyan helps turn Tacloban's resilience vision into public action."
        />

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($whatWeDoItems as $initiative)
                <x-cards.initiative :icon="$initiative['icon']" :title="$initiative['title']" :description="$initiative['description']" />
            @endforeach
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <x-section-header
                eyebrow="Archive Highlights"
                title="From mangrove restoration to the push for a flood-free Tacloban."
                description="These archive highlights carry PH Haiyan's work from coastal restoration and youth climate education to watershed proposals and the flood-control forum that brought agencies and stakeholders into a shared commitment."
            />

            <a href="{{ route('events.index') }}" class="btn-secondary">
                See all events
            </a>
        </div>

        <div class="mt-12 grid gap-6 xl:grid-cols-[minmax(0,1.12fr)_minmax(320px,0.88fr)]">
            @if ($featuredArchiveEvent)
                <article class="overflow-hidden rounded-[32px] border border-white/80 bg-white/92 shadow-soft">
                    <div class="grid gap-0 lg:grid-cols-[0.98fr_1.02fr]">
                        <div class="overflow-hidden">
                            <img src="{{ asset(ltrim($featuredArchiveEvent->image, '/')) }}" alt="{{ $featuredArchiveEvent->image_alt ?: $featuredArchiveEvent->title }}" loading="lazy" decoding="async" class="h-full min-h-80 w-full object-cover">
                        </div>

                        <div class="p-6 sm:p-7 lg:p-8">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="chip">{{ $featuredArchiveEvent->category }}</span>
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $featuredArchiveEvent->start_at?->format('Y') }}</span>
                            </div>

                            <h3 class="mt-4 font-display text-3xl font-semibold tracking-tight text-pine-950 sm:text-[2.45rem] sm:leading-[1.02]">
                                {{ $featuredArchiveEvent->title }}
                            </h3>

                            <p class="mt-4 text-base leading-8 text-slate-600">
                                {{ \Illuminate\Support\Str::limit($featuredArchiveEvent->summary, 220) }}
                            </p>

                            <div class="mt-6 rounded-[24px] border border-slate-200/80 bg-slate-50/80 px-5 py-5">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Archive note</div>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    {{ $featuredArchiveEvent->description }}
                                </p>
                            </div>

                            <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
                                <span>{{ $featuredArchiveEvent->venue }}</span>
                                <span class="hidden text-slate-300 sm:inline">&bull;</span>
                                <span>{{ $featuredArchiveEvent->location }}</span>
                            </div>

                            <div class="mt-7">
                                <a href="{{ $archiveReadMoreLink }}" class="btn-secondary">
                                    Read more in Forums
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endif

            <div class="grid gap-5">
                @foreach ($supportingArchiveEvents as $event)
                    <article class="overflow-hidden rounded-[28px] border border-white/80 bg-white/92 shadow-soft">
                        <div class="grid gap-0 sm:grid-cols-[148px_1fr]">
                            <img src="{{ asset(ltrim($event->image, '/')) }}" alt="{{ $event->image_alt ?: $event->title }}" loading="lazy" decoding="async" class="h-full min-h-44 w-full object-cover">

                            <div class="p-5 sm:p-6">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="chip">{{ $event->category }}</span>
                                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $event->start_at?->format('Y') }}</span>
                                </div>

                                <h3 class="mt-4 text-xl font-semibold leading-8 text-pine-950">
                                    {{ $event->title }}
                                </h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit($event->summary, 120) }}
                                </p>

                                <div class="mt-5">
                                    <a href="{{ route('events.index') }}#event-{{ $event->slug }}" class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-pine-50 px-4 py-2 text-sm font-semibold text-pine-900 transition hover:border-pine-300 hover:bg-pine-100">
                                        <span>Read more</span>
                                        <x-icon name="arrow-up-right" class="h-4 w-4" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="homepage-project-archive" class="section-shell py-12 lg:py-16">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <x-section-header
                eyebrow="Project Archive"
                title="Four core projects from the original PH Haiyan program archive."
            />

            <a href="{{ route('projects.index') }}#project-directory" class="btn-secondary">
                Open projects page
            </a>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($featuredProjects as $project)
                <article class="group overflow-hidden rounded-[30px] border border-white/80 bg-white/94 shadow-soft transition hover:-translate-y-1 hover:shadow-float">
                    <a href="{{ route('projects.index') }}#project-{{ $project->slug }}" class="block overflow-hidden">
                        <img src="{{ asset(ltrim((string) $project->image, '/')) }}" alt="{{ $project->image_alt ?: $project->title }}" loading="lazy" decoding="async" class="h-52 w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    </a>

                    <div class="p-5 sm:p-6">
                        <div class="chip">Project Archive</div>
                        <h3 class="mt-4 text-[1.45rem] font-semibold leading-8 text-pine-950">
                            <a href="{{ route('projects.index') }}#project-{{ $project->slug }}" class="transition hover:text-pine-800">
                                {{ $project->title }}
                            </a>
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit($project->summary, 120) }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('projects.index') }}#project-{{ $project->slug }}" class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-pine-50 px-4 py-2 text-sm font-semibold text-pine-900 transition hover:border-pine-300 hover:bg-pine-100">
                                <span>Read more</span>
                                <x-icon name="arrow-up-right" class="h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="section-shell py-14 lg:py-20">
        <div class="relative overflow-hidden rounded-[40px] border border-pine-900/10 bg-[linear-gradient(135deg,_#0d372b_0%,_#134839_58%,_#0e3429_100%)] px-6 py-10 text-white shadow-[0_24px_80px_rgba(7,17,13,0.15)] sm:px-10 lg:px-14 lg:py-14">
            <div
                class="pointer-events-none absolute inset-0"
                style="
                    background-image:
                        radial-gradient(circle at top left, rgba(94, 234, 212, 0.08), transparent 26%),
                        linear-gradient(180deg, rgba(13, 55, 43, 0.22) 0%, rgba(13, 55, 43, 0.42) 100%),
                        linear-gradient(90deg, rgba(10, 43, 33, 0.97) 0%, rgba(10, 43, 33, 0.965) 38%, rgba(10, 43, 33, 0.86) 48%, rgba(13, 55, 43, 0.76) 64%, rgba(13, 55, 43, 0.68) 100%),
                        url('{{ $impactBackground }}');
                    background-size: cover;
                    background-position: center;
                "
            ></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-32 bg-[linear-gradient(180deg,_rgba(255,255,255,0)_0%,_rgba(255,255,255,0.05)_100%)]"></div>

            <div class="relative max-w-4xl">
                <div class="eyebrow border-white/15 bg-white/5 text-white/70">Support the Mission</div>

                <h2 class="mt-5 max-w-3xl font-display text-4xl font-semibold leading-[1.02] text-white sm:text-5xl lg:text-[3.7rem]">
                    {{ $impact['title'] ?? 'Be part of a more resilient tomorrow.' }}
                </h2>

                <div class="mt-7 h-px w-24 bg-white/18"></div>

                <div class="mt-7 max-w-2xl space-y-4">
                    <p class="text-xl leading-9 text-white/92 sm:text-[1.45rem]">
                        {{ $impact['description'] ?? 'Your support helps protect coastlines, strengthen communities, and keep climate resilience in public view.' }}
                    </p>
                    <p class="text-base leading-8 text-white/66">
                        From mangrove restoration and public forums to watershed protection and civic advocacy in Tacloban City.
                    </p>
                </div>

                <div class="mt-10 flex flex-wrap gap-3">
                    @foreach (config('site.support.actions') as $action)
                        @php
                            $href = \Illuminate\Support\Str::startsWith($action['href'], ['#', 'http'])
                                ? $action['href']
                                : route(
                                    $action['href'],
                                    isset($action['inquiry']) ? ['inquiry' => $action['inquiry']] : []
                                ).($action['href'] === 'contact.index' ? '#contact-form' : '');
                        @endphp
                        <a
                            href="{{ $href }}"
                            class="inline-flex items-center justify-center rounded-full px-5 py-3.5 text-sm font-semibold transition {{ $loop->first
                                ? 'bg-white text-pine-950 shadow-[0_14px_34px_rgba(0,0,0,0.14)] hover:bg-pine-50'
                                : 'border border-white/18 bg-white/7 text-white hover:border-white/28 hover:bg-white/12' }}"
                        >
                            <x-icon :name="$action['icon']" class="mr-2 h-4 w-4" />
                            <span>{{ $action['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
