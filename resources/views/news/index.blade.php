@extends('layouts.app', [
    'pageTitle' => $page->meta_title,
    'pageDescription' => $page->meta_description,
    'seo' => [
        'canonical_url' => route('news.index'),
        'robots' => $activeCategory === 'all' ? null : 'noindex,follow',
    ],
])

@section('content')
    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16">
        <div class="overflow-hidden rounded-[34px] border border-white/80 bg-[linear-gradient(135deg,_rgba(255,255,255,0.96),_rgba(244,248,245,0.94))] shadow-soft">
            <div class="grid gap-6 px-6 py-7 sm:px-8 sm:py-8 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end lg:px-10">
                <div class="max-w-3xl">
                    <div class="text-xs font-semibold uppercase tracking-[0.3em] text-pine-700">News Archive</div>
                    <h2 class="mt-3 font-display text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">
                        Browse preserved public updates, campaigns, and field stories.
                    </h2>
                    <p class="mt-4 text-base leading-8 text-slate-600">
                        Each update now opens as a full local story page, so the archive stays readable and complete even after the old website is retired.
                    </p>
                </div>

                <div class="rounded-[24px] border border-slate-200/80 bg-white/88 px-5 py-4 text-sm leading-7 text-slate-600 shadow-soft">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Visible updates</div>
                    <div class="mt-2 text-3xl font-semibold tracking-tight text-pine-950">{{ $totalPosts }}</div>
                    <div class="mt-1">{{ $activeCategory === 'all' ? 'All categories' : $activeCategory }}</div>
                </div>
            </div>

            <div class="border-t border-slate-200/80 px-6 py-6 sm:px-8 lg:px-10">
                <div class="text-xs font-semibold uppercase tracking-[0.26em] text-pine-700">Filter by category</div>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a
                        href="{{ route('news.index') }}"
                        class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold transition {{ $activeCategory === 'all' ? 'border-pine-900 bg-pine-900 text-white shadow-soft' : 'border-pine-100 bg-white text-pine-900 hover:border-pine-200 hover:bg-pine-50' }}"
                    >
                        All updates
                    </a>
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('news.index', ['category' => $category]) }}"
                            class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold transition {{ $activeCategory === $category ? 'border-pine-900 bg-pine-900 text-white shadow-soft' : 'border-pine-100 bg-white text-pine-900 hover:border-pine-200 hover:bg-pine-50' }}"
                        >
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if ($featuredPost)
        <section class="section-shell py-6 lg:py-8">
            <x-section-header
                eyebrow="Featured Story"
                title="Start with the most visible public update in the archive."
                description="The featured story anchors the archive and gives readers a fuller view of one issue before moving into the wider update record."
            />

            <div class="mt-10">
                <x-cards.news :post="$featuredPost" featured />
            </div>
        </section>
    @endif

    <section class="section-shell py-10 lg:py-14">
        <x-section-header
            eyebrow="Archive by Year"
            title="Recent and older updates remain grouped in one readable record."
            description="The archive below keeps public statements, campaign notices, and field updates available beyond the homepage."
        />

        <div class="mt-10 space-y-10">
            @forelse ($archiveGroups as $year => $group)
                <section>
                    <div class="flex flex-wrap items-center gap-4">
                        <h2 class="font-display text-4xl font-semibold tracking-tight text-pine-950">{{ $year }}</h2>
                        <span class="rounded-full border border-pine-100 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-pine-700 shadow-soft">
                            {{ $group->count() }} update{{ $group->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div class="mt-6 space-y-5">
                        @foreach ($group as $post)
                            <article class="overflow-hidden rounded-[30px] border border-white/80 bg-white/92 p-5 shadow-soft sm:p-6">
                                <div class="grid gap-5 md:grid-cols-[180px_minmax(0,1fr)_auto] md:items-center">
                                    <a href="{{ route('news.show', $post) }}" class="block overflow-hidden rounded-[22px] border border-slate-100 bg-slate-50">
                                        <img src="{{ asset(ltrim($post->image, '/')) }}" alt="{{ $post->image_alt ?: $post->title }}" loading="lazy" decoding="async" class="aspect-[5/3] w-full object-cover transition duration-500 hover:scale-[1.03]">
                                    </a>

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="chip">{{ $post->category }}</span>
                                            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $post->published_at?->format('M d, Y') }}</span>
                                            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $post->reading_time }} min read</span>
                                        </div>

                                        <h3 class="mt-4 font-display text-2xl font-semibold leading-[1.08] text-pine-950">
                                            <a href="{{ route('news.show', $post) }}" class="transition hover:text-pine-800">
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        <p class="mt-3 max-w-3xl text-sm leading-8 text-slate-600">
                                            {{ $post->excerpt }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col gap-3 md:min-w-[11rem] md:items-end">
                                        <a href="{{ route('news.show', $post) }}" class="btn-primary gap-2">
                                            <x-icon name="eye" class="h-4 w-4" />
                                            Read story
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-[30px] border border-dashed border-slate-300 bg-white/80 px-6 py-10 text-center text-sm leading-8 text-slate-500 shadow-soft">
                    No updates match this category yet.
                </div>
            @endforelse
        </div>
    </section>
@endsection
