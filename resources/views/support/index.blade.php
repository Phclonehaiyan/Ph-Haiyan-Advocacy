@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $content = $page->content ?? [];
    @endphp

    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Support Pathways"
            title="{{ config('site.support.headline') }}"
            :description="config('site.support.summary')"
        />

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach (config('site.support.actions') as $action)
                @php
                    $href = \Illuminate\Support\Str::startsWith($action['href'], ['#', 'http'])
                        ? $action['href']
                        : route(
                            $action['href'],
                            isset($action['inquiry']) ? ['inquiry' => $action['inquiry']] : []
                        ).($action['href'] === 'contact.index' ? '#contact-form' : '');
                @endphp
                <article class="surface-card h-full">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-pine-50 text-pine-700">
                        <x-icon :name="$action['icon']" class="h-6 w-6" />
                    </div>
                    <h3 class="mt-6 text-2xl font-semibold text-pine-950">{{ $action['label'] }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $action['description'] }}</p>
                    <a href="{{ $href }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-pine-800 transition hover:text-pine-950">
                        Continue
                        <x-icon name="arrow-up-right" class="h-4 w-4" />
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="surface-card">
                <div class="eyebrow">Why Support Matters</div>
                <h2 class="mt-5 font-display text-4xl font-semibold text-pine-950">Support strengthens continuity, not just one-off activities.</h2>
                <p class="mt-5 text-base leading-8 text-slate-600">
                    Environmental advocacy becomes more trusted when the public can see consistent activity, stronger partnerships, and a clear pathway from awareness to action. Support helps keep that continuity alive.
                </p>
            </div>

            <div class="grid gap-4">
                @foreach ($content['benefits'] ?? [] as $benefit)
                    <div class="surface-card flex items-start gap-4">
                        <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-2xl bg-teal-50 text-teal-700">
                            <x-icon name="spark" class="h-5 w-5" />
                        </div>
                        <p class="text-sm leading-7 text-slate-600">{{ $benefit }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-shell py-14 lg:py-20">
        <div
            class="relative overflow-hidden rounded-[32px] border border-pine-900/10 px-8 py-10 text-white shadow-soft sm:px-10 lg:px-12"
            style="
                background-image:
                    linear-gradient(90deg, rgba(10, 43, 33, 0.96) 0%, rgba(10, 43, 33, 0.9) 34%, rgba(13, 55, 43, 0.8) 60%, rgba(13, 55, 43, 0.72) 100%),
                    linear-gradient(180deg, rgba(13, 55, 43, 0.2) 0%, rgba(13, 55, 43, 0.4) 100%),
                    url('{{ asset('images/imported/gallery/first-interagency-meeting.jpg') }}');
                background-size: cover;
                background-position: center;
                background-color: #0f3d2e;
            "
        >
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.05),_transparent_24%),radial-gradient(circle_at_80%_50%,_rgba(255,255,255,0.06),_transparent_18%)]"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-[linear-gradient(180deg,_rgba(255,255,255,0)_0%,_rgba(255,255,255,0.04)_100%)]"></div>

            <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <div class="eyebrow border-white/15 bg-white/5 text-white/70">Next Step</div>
                    <h2 class="mt-5 font-display text-4xl font-semibold text-white">Ready to support, volunteer, or partner?</h2>
                    <p class="mt-4 max-w-2xl text-lg leading-8 text-white/75">Use the contact page to start a conversation with PH Haiyan about donations, volunteer work, partnerships, or other ways to strengthen the mission.</p>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact.index') }}#contact-form" class="btn-primary bg-white text-pine-950 hover:bg-sand-100">
                        Contact Us
                    </a>
                    <a href="{{ route('home') }}" class="btn-ghost">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
