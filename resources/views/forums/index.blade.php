@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    @if ($leadStory)
        @php
            $leadStoryParagraphs = collect(preg_split("/\n\s*\n/", trim($leadStory->body ?? '')))
                ->map(fn ($paragraph) => trim($paragraph))
                ->filter()
                ->values();
        @endphp

        <section id="topic-{{ $leadStory->slug }}" class="section-shell py-12 lg:py-16">
            <div class="overflow-hidden rounded-[34px] border border-white/80 bg-white/92 shadow-soft">
                <div class="grid gap-0 lg:grid-cols-[0.92fr_1.08fr]">
                    <div class="overflow-hidden">
                        <img src="{{ asset(ltrim($leadStory->image ?? '/images/imported/floodcontrol/flood-control-forum.jpg', '/')) }}" alt="{{ $leadStory->title }}" class="h-full min-h-96 w-full object-cover">
                    </div>

                    <div class="p-6 sm:p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="chip">{{ $leadStory->category }}</span>
                            @if ($leadStory->is_pinned)
                                <span class="chip bg-pine-50 text-pine-700 ring-pine-100">Pinned Story</span>
                            @endif
                            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Old-site forum story</span>
                        </div>

                        <h2 class="mt-5 font-display text-3xl font-semibold tracking-tight text-pine-950 sm:text-[2.7rem] sm:leading-[1.02]">
                            {{ $leadStory->title }}
                        </h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">
                            {{ $leadStory->summary }}
                        </p>

                        <div class="mt-8 space-y-5 text-[1rem] leading-8 text-slate-600">
                            @foreach ($leadStoryParagraphs as $paragraph)
                                @if (\Illuminate\Support\Str::upper($paragraph) === $paragraph && \Illuminate\Support\Str::length($paragraph) < 60)
                                    <h3 class="pt-2 text-sm font-semibold uppercase tracking-[0.24em] text-pine-700">{{ $paragraph }}</h3>
                                @else
                                    <p>{{ $paragraph }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Forum Categories"
            title="Discussion areas shaped by PH Haiyan's real public concerns."
            description="These categories reflect the issues already visible across the organization's forums, letters, and campaign work."
        />

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($page->content['categories'] ?? [] as $category)
                <article class="surface-card h-full">
                    <div class="chip">Forum Category</div>
                    <h3 class="mt-5 text-2xl font-semibold text-pine-950">{{ $category['title'] }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $category['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
