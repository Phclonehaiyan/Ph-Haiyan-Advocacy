@extends('layouts.app', [
    'pageTitle' => $query ? 'Search results for "'.$query.'"' : 'Site Search',
    'pageDescription' => 'Search across PH Haiyan Advocacy Inc. pages, letters, records, gallery items, and archive content.',
    'seo' => [
        'canonical_url' => route('search.index'),
        'robots' => 'noindex,follow',
    ],
])

@section('content')
    @php
        $searchHeading = $query ? 'Results for "'.$query.'"' : 'Search the PH Haiyan website.';
    @endphp

    <section class="section-shell py-14 lg:py-18">
        <x-section-header
            eyebrow="Site Search"
            :title="$searchHeading"
            :description="$query
                ? 'Browse matching pages, letters, archive records, and content from across the website.'
                : 'Enter a keyword to search PH Haiyan pages, letters, news, events, gallery items, and archive content.'"
        />

        <div class="mt-8 rounded-[32px] border border-white/80 bg-white/90 p-5 shadow-soft sm:p-6">
            <form action="{{ route('search.index') }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-center">
                <label class="relative block">
                    <span class="sr-only">Search the website</span>
                    <x-icon name="search" class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        type="search"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Search pages, letters, projects, gallery, events..."
                        class="form-input rounded-[24px] border-pine-100 bg-sand-50 py-4 pl-12 pr-5 text-base"
                    >
                </label>

                <button type="submit" class="btn-primary min-w-[160px]">
                    Search site
                </button>
            </form>
        </div>

        @if ($query === '')
            <div class="mt-10 rounded-[30px] border border-dashed border-slate-300 bg-white/80 px-6 py-10 text-center text-sm leading-8 text-slate-500 shadow-soft">
                Start with a keyword like <span class="font-semibold text-pine-900">Tacloban</span>, <span class="font-semibold text-pine-900">mangrove</span>, <span class="font-semibold text-pine-900">PrimeWater</span>, or <span class="font-semibold text-pine-900">flood control</span>.
            </div>
        @elseif ($results->isEmpty())
            <div class="mt-10 rounded-[30px] border border-dashed border-slate-300 bg-white/80 px-6 py-10 text-center text-sm leading-8 text-slate-500 shadow-soft">
                No results matched <span class="font-semibold text-pine-900">&ldquo;{{ $query }}&rdquo;</span>. Try another keyword or a shorter phrase.
            </div>
        @else
            <div class="mt-10 flex items-center justify-between gap-4">
                <div class="text-sm leading-7 text-slate-500">
                    <span class="font-semibold text-pine-950">{{ $results->count() }}</span> results found
                </div>
            </div>

            <div class="mt-8 space-y-10">
                @foreach ($resultGroups as $label => $group)
                    <section>
                        <div class="flex items-center gap-3">
                            <span class="chip">{{ $label }}</span>
                            <span class="text-sm text-slate-400">{{ $group->count() }} matches</span>
                        </div>

                        <div class="mt-5 grid gap-5 lg:grid-cols-2">
                            @foreach ($group as $result)
                                <article class="rounded-[30px] border border-white/80 bg-white/92 p-6 shadow-soft transition hover:-translate-y-0.5 hover:shadow-float">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="text-xs font-semibold uppercase tracking-[0.22em] text-pine-700">{{ $result['meta'] }}</span>
                                        <span class="h-px flex-1 bg-slate-100"></span>
                                    </div>

                                    <h2 class="mt-4 text-2xl font-semibold leading-tight text-pine-950">
                                        <a href="{{ $result['url'] }}" class="transition hover:text-pine-800">
                                            {{ $result['title'] }}
                                        </a>
                                    </h2>

                                    <p class="mt-3 text-sm leading-8 text-slate-600">
                                        {{ $result['summary'] }}
                                    </p>

                                    <div class="mt-6">
                                        <a href="{{ $result['url'] }}" class="inline-flex items-center gap-2 text-sm font-semibold text-pine-800 transition hover:text-pine-950">
                                            <span>Open result</span>
                                            <x-icon name="arrow-up-right" class="h-4 w-4" />
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </section>
@endsection
