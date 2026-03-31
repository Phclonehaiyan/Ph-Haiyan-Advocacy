@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $content = $page->content ?? [];
        $projectArchive = $featuredProjects;
        $overviewImage = asset(ltrim((string) data_get($content, 'overview_image', '/images/imported/gallery/first-interagency-meeting.jpg'), '/'));
    @endphp

    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
            <div class="space-y-5 text-base leading-8 text-slate-600">
                <x-section-header
                    eyebrow="Program Overview"
                    title="How PH Haiyan turns climate resilience into public action."
                />

                @foreach ($content['intro'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="image-frame h-full min-h-[340px]">
                <img src="{{ $overviewImage }}" alt="Climate resilience and community action program of PH Haiyan Advocacy Inc." loading="lazy" decoding="async" class="h-full min-h-[340px] w-full object-cover">
            </div>
        </div>
    </section>

    <section id="project-archive" class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Project Archive"
            title="Project records preserved from the original What We Do page."
            description="These cards carry the older PH Haiyan project archive into the new site, with the original focus on coastal restoration, local livelihood support, and climate adaptation work."
        />

        <div class="mt-12 grid gap-6 lg:grid-cols-2">
            @foreach ($projectArchive as $project)
                <article id="{{ $project->slug }}" class="scroll-mt-28 overflow-hidden rounded-[32px] border border-white/80 bg-white/94 shadow-soft">
                    <div class="grid gap-0 xl:grid-cols-[240px_1fr]">
                        <div class="overflow-hidden">
                            <img src="{{ asset(ltrim((string) $project->image, '/')) }}" alt="{{ $project->image_alt ?: $project->title }}" loading="lazy" decoding="async" class="h-full min-h-72 w-full object-cover">
                        </div>

                        <div class="p-6 sm:p-7">
                            <div class="chip">Historic Project</div>
                            <h3 class="mt-4 text-2xl font-semibold leading-tight text-pine-950">
                                {{ $project->title }}
                            </h3>
                            <p class="mt-4 text-base leading-8 text-slate-600">
                                {{ $project->summary }}
                            </p>

                            <div class="mt-6 rounded-[24px] border border-slate-200/80 bg-[linear-gradient(135deg,_rgba(248,250,252,0.95),_rgba(255,255,255,0.92))] px-5 py-5">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">
                                    Project details
                                </div>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    {{ $project->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Signature Work"
            title="Milestones that show the mission in practice."
            description="From mangrove restoration to youth climate education and flood-control advocacy, these efforts show how the vision becomes visible on the ground."
            align="center"
        />

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach ($content['stories'] ?? [] as $story)
                <article class="surface-card h-full">
                    <div class="chip">Initiative</div>
                    <h3 class="mt-5 text-2xl font-semibold text-pine-950">{{ $story['title'] }}</h3>
                    <p class="mt-4 text-sm leading-8 text-slate-600">{{ $story['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="section-shell py-14 lg:py-20">
        <div
            class="relative overflow-hidden rounded-[36px] border border-pine-900/10 px-6 py-10 text-white shadow-float sm:px-10 lg:px-14"
            style="
                background-image:
                    linear-gradient(90deg, rgba(10, 43, 33, 0.96) 0%, rgba(10, 43, 33, 0.9) 34%, rgba(13, 55, 43, 0.8) 60%, rgba(13, 55, 43, 0.72) 100%),
                    linear-gradient(180deg, rgba(13, 55, 43, 0.2) 0%, rgba(13, 55, 43, 0.4) 100%),
                    url('{{ asset('images/imported/events/event-balugo-watershed.jpg') }}');
                background-size: cover;
                background-position: center;
                background-color: #0f3d2e;
            "
        >
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.05),_transparent_24%),radial-gradient(circle_at_80%_50%,_rgba(255,255,255,0.06),_transparent_18%)]"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-[linear-gradient(180deg,_rgba(255,255,255,0)_0%,_rgba(255,255,255,0.04)_100%)]"></div>

            <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <div class="eyebrow border-white/15 bg-white/5 text-white/70">Support the Work</div>
                    <h2 class="mt-5 font-display text-4xl font-semibold text-white">Programs like these grow through committed partners, volunteers, and supporters.</h2>
                    <p class="mt-4 max-w-2xl text-lg leading-8 text-white/75">{{ config('site.support.summary') }}</p>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('support') }}" class="btn-primary bg-white text-pine-950 hover:bg-sand-100">Support the Mission</a>
                    <a href="{{ route('contact.index') }}" class="btn-ghost">Start a Partnership</a>
                </div>
            </div>
        </div>
    </section>
@endsection
