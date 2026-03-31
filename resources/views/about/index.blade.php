@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $content = $page->content ?? [];
        $logoImage = asset('images/brand/ph-haiyan-logo.png');
        $whoWeAreImage = asset(ltrim((string) data_get($content, 'who_we_are_image', '/images/imported/floodcontrol/building-resilience.jpg'), '/'));
        $ceoImage = asset(ltrim((string) data_get($content, 'ceo.image', '/uploads/about/ceo-pete-ilagan.png'), '/'));
        $ceoName = data_get($content, 'ceo.name', 'Pete L. Ilagan');
        $ceoRole = data_get($content, 'ceo.role', 'CEO, PH Haiyan Advocacy Inc.');
        $ceoDescription = data_get($content, 'ceo.description', 'Leading PH Haiyan with a civic, environmental, and resilience-centered vision for Tacloban and Eastern Visayas.');
        $ceoHighlights = data_get($content, 'ceo.highlights', [
            'Pete L. Ilagan helped shape PH Haiyan from a citizen-led response into a long-view climate resilience advocacy rooted in Tacloban.',
            'As a survivor of Super Typhoon Haiyan and a long-time conservation advocate, he pushed for practical environmental rehabilitation when local climate-response plans were still missing.',
            'His leadership helped gather professionals, advocates, and community partners around a shared goal: building systems that make Tacloban and Eastern Visayas more prepared for future climate threats.',
        ]);
        $missionVisionEyebrow = data_get($content, 'mission_vision.eyebrow', 'Mission and Vision');
        $missionVisionHeading = data_get($content, 'mission_vision.heading', "The principles guiding PH Haiyan's work in Tacloban and Eastern Visayas.");
        $missionVisionDescription = data_get($content, 'mission_vision.description', "These statements define the organization's present responsibility and the future it is working to help shape.");
        $missionVisionBadge = data_get($content, 'mission_vision.badge', 'Core Direction');
        $missionVisionBackgroundImage = asset(ltrim((string) data_get($content, 'mission_vision.background_image', '/images/imported/events/event-balugo-watershed.jpg'), '/'));
        $missionLabel = data_get($content, 'mission_vision.mission_label', 'Mission');
        $missionTitle = data_get($content, 'mission_vision.mission_title', 'What PH Haiyan is called to do now.');
        $mission = data_get($content, 'mission_vision.mission_quote', 'Overcoming climate threats by building resilient interdependent systems in Tacloban City and across Eastern Visayas, Philippines.');
        $visionLabel = data_get($content, 'mission_vision.vision_label', 'Vision');
        $visionTitle = data_get($content, 'mission_vision.vision_title', 'What PH Haiyan is working toward.');
        $vision = data_get($content, 'mission_vision.vision_quote', 'A future where Tacloban City stands as a model for climate resilience and climate-smart development in the Philippines.');
    @endphp

    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[1.08fr_0.92fr] lg:items-start">
            <div class="space-y-5 text-base leading-8 text-slate-600">
                <x-section-header
                    eyebrow="Who We Are"
                    title="A community-based organization focused on climate resilience and environmental protection."
                />

                @foreach ($content['who_we_are'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="image-frame h-full min-h-[320px]">
                <img src="{{ $whoWeAreImage }}" alt="PH Haiyan community resilience and environmental advocacy work in Tacloban" loading="lazy" decoding="async" class="h-full min-h-[320px] w-full object-cover">
            </div>
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <div class="relative flex min-h-[320px] items-center justify-center overflow-hidden rounded-[36px] px-6 py-8">
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(18,116,133,0.08),_transparent_65%)]"></div>
                <img src="{{ $logoImage }}" alt="PH Haiyan Advocacy Inc. logo" class="relative z-10 w-full max-w-[320px] object-contain drop-shadow-[0_18px_28px_rgba(10,31,24,0.08)]">
            </div>

            <div class="space-y-5 text-base leading-8 text-slate-600">
                <x-section-header
                    eyebrow="Our Logo"
                    title="A mark that reflects restoration, water systems, trust, and preparedness."
                />

                @foreach ($content['logo'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[0.88fr_1.12fr] lg:items-center">
            <div class="image-frame max-w-md">
                <img src="{{ $ceoImage }}" alt="{{ $ceoName }}, {{ $ceoRole }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
            </div>

            <div class="space-y-5 text-base leading-8 text-slate-600">
                <x-section-header
                    eyebrow="Our CEO"
                    :title="$ceoName"
                    :description="$ceoDescription"
                />
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-pine-700">{{ $ceoRole }}</p>

                @foreach ($ceoHighlights as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div
            class="mission-vision-panel relative overflow-hidden rounded-[28px] border border-pine-900/10 px-6 py-8 text-white shadow-soft sm:px-8 sm:py-10 lg:px-10 lg:py-12"
            style="
                background-image:
                    linear-gradient(90deg, rgba(10, 43, 33, 0.985) 0%, rgba(10, 43, 33, 0.965) 30%, rgba(13, 55, 43, 0.9) 56%, rgba(13, 55, 43, 0.8) 100%),
                    linear-gradient(180deg, rgba(13, 55, 43, 0.18) 0%, rgba(13, 55, 43, 0.38) 100%),
                    url('{{ $missionVisionBackgroundImage }}');
                background-size: cover;
                background-position: center;
                background-color: #0f3d2e;
            "
        >
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.04),_transparent_24%),radial-gradient(circle_at_80%_50%,_rgba(255,255,255,0.03),_transparent_18%)]"></div>
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(90deg,_rgba(8,34,26,0.24)_0%,_rgba(8,34,26,0.06)_42%,_rgba(255,255,255,0)_100%)]"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-[linear-gradient(180deg,_rgba(255,255,255,0)_0%,_rgba(255,255,255,0.03)_100%)]"></div>

            <div class="relative">
                <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div class="max-w-4xl">
                        <div class="inline-flex items-center rounded-full border border-white/18 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-white/92 backdrop-blur">
                            {{ $missionVisionEyebrow }}
                        </div>
                        <h2 class="mt-5 max-w-4xl font-display text-[clamp(2.8rem,5.5vw,4.8rem)] font-semibold leading-[0.98] text-white">
                            {{ $missionVisionHeading }}
                        </h2>
                        <p class="mt-6 max-w-2xl text-base leading-8 text-white/84 sm:text-lg">
                            {{ $missionVisionDescription }}
                        </p>
                    </div>

                    <div class="w-fit rounded-full border border-white/18 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white/90 shadow-soft backdrop-blur">
                        {{ $missionVisionBadge }}
                    </div>
                </div>

                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    <article class="mission-vision-card relative overflow-hidden rounded-[24px] border border-white/16 bg-[linear-gradient(135deg,_rgba(255,255,255,0.18),_rgba(255,255,255,0.12))] p-7 shadow-[0_20px_50px_rgba(7,17,13,0.12)] backdrop-blur-md sm:p-8">
                        <div class="pointer-events-none absolute inset-x-0 top-0 h-1.5 bg-[linear-gradient(90deg,_rgba(255,255,255,0.9),_rgba(94,234,212,0.42))]"></div>
                        <div class="pointer-events-none absolute right-6 top-14 font-display text-7xl leading-none text-white/10 sm:text-8xl">
                            &ldquo;
                        </div>
                        <div class="inline-flex items-center rounded-full border border-white/14 bg-white/6 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-white/82">{{ $missionLabel }}</div>
                        <h3 class="mt-5 font-display text-[1.4rem] font-semibold leading-[1.12] text-white sm:text-[1.55rem]">{{ $missionTitle }}</h3>
                        <div class="mt-6 h-px w-16 bg-white/18"></div>
                        <blockquote class="mt-6 max-w-xl font-display text-[1.9rem] leading-[1.35] text-white sm:text-[2.15rem] lg:text-[2.35rem]">
                            "{{ $mission }}"
                        </blockquote>
                    </article>

                    <article class="mission-vision-card relative overflow-hidden rounded-[24px] border border-white/16 bg-[linear-gradient(135deg,_rgba(255,255,255,0.18),_rgba(255,255,255,0.12))] p-7 shadow-[0_20px_50px_rgba(7,17,13,0.12)] backdrop-blur-md sm:p-8">
                        <div class="pointer-events-none absolute inset-x-0 top-0 h-1.5 bg-[linear-gradient(90deg,_rgba(94,234,212,0.48),_rgba(255,255,255,0.8))]"></div>
                        <div class="pointer-events-none absolute right-6 top-14 font-display text-7xl leading-none text-white/10 sm:text-8xl">
                            &ldquo;
                        </div>
                        <div class="inline-flex items-center rounded-full border border-white/14 bg-white/6 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-white/82">{{ $visionLabel }}</div>
                        <h3 class="mt-5 font-display text-[1.4rem] font-semibold leading-[1.12] text-white sm:text-[1.55rem]">{{ $visionTitle }}</h3>
                        <div class="mt-6 h-px w-16 bg-white/18"></div>
                        <blockquote class="mt-6 max-w-xl font-display text-[1.9rem] leading-[1.35] text-white sm:text-[2.15rem] lg:text-[2.35rem]">
                            "{{ $vision }}"
                        </blockquote>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="mx-auto max-w-5xl">
            <x-section-header
                eyebrow="Our Story"
                title="Born from the devastation of Haiyan and shaped by the need for long-term action."
            />

            <div class="mt-8 space-y-6 text-base leading-8 text-slate-600">
                @foreach ($content['story'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="relative overflow-hidden rounded-[36px] border border-white/80 bg-white/94 px-6 py-8 shadow-soft sm:px-8 sm:py-10 lg:px-12 lg:py-12">
            <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-1/3 bg-[radial-gradient(circle_at_center,_rgba(18,116,133,0.12),_transparent_62%)] lg:block"></div>

            <div class="relative max-w-4xl">
                <div>
                    <x-section-header
                        eyebrow="Why Climate Resilience Matters"
                        :title="$content['why_resilience']['heading'] ?? 'Why climate resilience matters'"
                    />

                    <div class="mt-8 space-y-5 text-base leading-8 text-slate-600">
                        @foreach (data_get($content, 'why_resilience.paragraphs', []) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell py-14 lg:py-20">
        <div class="relative overflow-hidden rounded-[32px] border border-pine-900/10 bg-gradient-to-br from-pine-950 via-pine-900 to-teal-800 text-white shadow-soft">
            <div
                class="pointer-events-none absolute inset-0"
                style="
                    background-image:
                        linear-gradient(90deg, rgba(10, 43, 33, 0.96) 0%, rgba(10, 43, 33, 0.9) 34%, rgba(13, 55, 43, 0.8) 58%, rgba(13, 55, 43, 0.72) 100%),
                        linear-gradient(180deg, rgba(13, 55, 43, 0.18) 0%, rgba(13, 55, 43, 0.42) 100%),
                        radial-gradient(circle at top right, rgba(255, 255, 255, 0.06), transparent 24%),
                        url('{{ asset('images/hero/mangrove-river-bright.jpg') }}');
                    background-size: cover;
                    background-position: center;
                "
            ></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-[linear-gradient(180deg,_rgba(255,255,255,0)_0%,_rgba(255,255,255,0.04)_100%)]"></div>

            <div class="relative p-8 sm:p-10 lg:p-12">
            <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <div class="eyebrow border-white/15 bg-white/5 text-white/70">Get Involved</div>
                    <h2 class="mt-5 font-display text-4xl font-semibold text-white">{{ $content['cta']['heading'] ?? 'Build the next chapter with us.' }}</h2>
                    <p class="mt-4 max-w-2xl text-lg leading-8 text-white/75">{{ $content['cta']['description'] ?? config('site.support.summary') }}</p>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('support') }}" class="btn-primary bg-white text-pine-950 hover:bg-sand-100">
                        Support the Mission
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn-ghost">
                        Contact the Team
                    </a>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
