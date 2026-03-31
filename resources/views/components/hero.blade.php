@props([
    'eyebrow' => null,
    'title',
    'tagline' => null,
    'chips' => [],
    'description' => null,
    'actions' => [],
    'image' => null,
    'showVisual' => true,
    'immersive' => false,
    'stats' => [],
    'compact' => false,
])

@php
    $spacing = $compact ? 'py-14 lg:py-20' : 'py-20 lg:py-28';
    $hideImagesForCompact = $compact && ! $immersive;
    $backgroundImage = (! $showVisual && $image && ! $hideImagesForCompact) ? asset(ltrim($image, '/')) : null;
    $isImmersive = $immersive && $backgroundImage;
    $useCompactBackground = false;
    $backgroundImage = ($useCompactBackground || (! $showVisual && $image && ! $hideImagesForCompact))
        ? asset(ltrim($image, '/'))
        : null;
    $showVisualPanel = $showVisual && ! $useCompactBackground && ! $hideImagesForCompact;
    $displayTitle = $isImmersive
        ? \Illuminate\Support\Str::of($title)->lower()->title()->toString()
        : $title;
@endphp

@if ($isImmersive)
    <section class="relative px-4 py-4 lg:px-7 lg:py-6">
        <div class="relative isolate overflow-hidden rounded-[36px] bg-[#09130f] shadow-[0_28px_90px_rgba(7,17,13,0.22)]">
            <div class="absolute inset-0">
                <img src="{{ $backgroundImage }}" alt="" class="h-full w-full scale-[1.02] object-cover" style="object-position: center 52%;">
                <div class="absolute inset-0 bg-[linear-gradient(180deg,_rgba(8,18,14,0.16)_0%,_rgba(8,18,14,0.32)_26%,_rgba(8,18,14,0.68)_100%)]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.08),_transparent_30%),linear-gradient(90deg,_rgba(8,18,14,0.28)_0%,_rgba(8,18,14,0.1)_18%,_rgba(8,18,14,0.1)_82%,_rgba(8,18,14,0.3)_100%)]"></div>
            </div>

            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-48 bg-[linear-gradient(180deg,_rgba(8,18,14,0)_0%,_rgba(8,18,14,0.68)_100%)]"></div>

            <div class="relative mx-auto flex min-h-[72vh] max-w-[1500px] items-center justify-center px-6 py-20 sm:px-10 lg:min-h-[82vh] lg:px-14 lg:py-28">
                <div class="mx-auto max-w-5xl text-center">
                    @if ($eyebrow)
                        <div class="text-sm font-semibold tracking-[0.12em] text-[#f1c84b] sm:text-base lg:text-[1.1rem]">
                            {{ rtrim($eyebrow, '.') }}
                        </div>
                    @endif

                    <h1 class="mt-6 font-sans text-5xl font-extrabold leading-[0.9] tracking-[-0.05em] text-white [text-shadow:0_18px_50px_rgba(0,0,0,0.34)] sm:text-6xl lg:text-8xl xl:text-[7rem]">
                        {{ $displayTitle }}
                    </h1>

                    @if ($description)
                        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-white/82">
                            {{ $description }}
                        </p>
                    @endif

                    @if ($chips !== [])
                        <div class="mt-10 flex flex-wrap justify-center gap-4">
                            @foreach ($chips as $chip)
                                <div class="inline-flex min-w-[150px] items-center justify-center rounded-full bg-[#78d6a7] px-6 py-3 text-lg font-semibold text-[#0f2f23] shadow-[0_12px_30px_rgba(8,18,14,0.18)]">
                                    {{ $chip }}
                                </div>
                            @endforeach
                        </div>
                    @elseif ($tagline)
                        <div class="mt-6 text-xs font-semibold uppercase tracking-[0.26em] text-white/82 sm:text-sm">
                            {{ $tagline }}
                        </div>
                    @endif

                    @if ($actions !== [])
                        <div class="mt-10 flex flex-wrap justify-center gap-4">
                            @foreach ($actions as $action)
                                <a href="{{ $action['href'] }}" class="{{ ($action['variant'] ?? 'primary') === 'secondary' ? 'btn-secondary' : 'btn-primary' }}">
                                    {{ $action['label'] }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@else
    <section class="relative overflow-hidden {{ $spacing }}">
        @if ($backgroundImage)
            <div class="absolute inset-0">
                <img
                    src="{{ $backgroundImage }}"
                    alt=""
                    class="absolute inset-0 h-full w-full object-cover opacity-[0.26]"
                    style="object-position: center 42%;"
                >
                @if ($useCompactBackground)
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,_rgba(248,246,241,0.94)_0%,_rgba(248,246,241,0.965)_44%,_rgba(248,246,241,0.992)_100%)]"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(90deg,_rgba(247,244,238,0.975)_0%,_rgba(247,244,238,0.9)_24%,_rgba(247,244,238,0.86)_50%,_rgba(247,244,238,0.9)_76%,_rgba(247,244,238,0.975)_100%)]"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_22%,_rgba(15,61,46,0.10),_transparent_22%),radial-gradient(circle_at_84%_18%,_rgba(16,118,135,0.08),_transparent_20%)]"></div>
                    <div class="absolute inset-x-0 top-0 h-20 bg-[linear-gradient(180deg,_rgba(255,255,255,0.06)_0%,_rgba(255,255,255,0)_100%)]"></div>
                    <div class="absolute inset-y-0 left-0 w-[34%] bg-[radial-gradient(circle_at_left,_rgba(255,255,255,0.10),_transparent_62%)]"></div>
                @else
                    <div class="absolute inset-0 bg-[linear-gradient(90deg,_rgba(248,250,247,0.92)_0%,_rgba(248,250,247,0.82)_42%,_rgba(248,250,247,0.9)_100%)]"></div>
                @endif
            </div>
        @endif

        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(15,61,46,0.18),_transparent_48%),radial-gradient(circle_at_bottom_right,_rgba(16,118,135,0.16),_transparent_36%)]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-pine-200 to-transparent"></div>

        <div class="section-shell relative grid items-center gap-10 {{ $showVisualPanel ? 'lg:grid-cols-[1.15fr_0.85fr]' : '' }}">
            <div class="max-w-3xl">
                @if ($eyebrow)
                    <div class="eyebrow">{{ $eyebrow }}</div>
                @endif

                <h1 class="display-title mt-5 max-w-4xl text-balance text-pine-950">
                    {{ $displayTitle }}
                </h1>

                @if ($tagline)
                    <div class="mt-4 text-sm font-semibold uppercase tracking-[0.3em] text-pine-700">
                        {{ $tagline }}
                    </div>
                @endif

                @if ($description)
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                        {{ $description }}
                    </p>
                @endif

                @if ($actions !== [])
                    <div class="mt-8 flex flex-wrap gap-4">
                        @foreach ($actions as $action)
                            <a href="{{ $action['href'] }}" class="{{ ($action['variant'] ?? 'primary') === 'secondary' ? 'btn-secondary' : 'btn-primary' }}">
                                {{ $action['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if ($stats !== [])
                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        @foreach ($stats as $stat)
                            <div class="rounded-[28px] border border-pine-100 bg-white/80 px-5 py-4 shadow-soft backdrop-blur">
                                <div class="text-sm font-semibold uppercase tracking-[0.22em] text-pine-700">{{ $stat['value'] }}</div>
                                <div class="mt-2 text-sm leading-6 text-slate-600">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($showVisualPanel)
                <div class="relative">
                    <div class="absolute -left-8 top-8 hidden h-24 w-24 rounded-full bg-teal-300/25 blur-2xl lg:block"></div>
                    <div class="absolute -right-4 bottom-8 hidden h-28 w-28 rounded-full bg-pine-300/25 blur-2xl lg:block"></div>
                    <div class="image-frame relative">
                        @if ($image)
                            <img src="{{ asset(ltrim($image, '/')) }}" alt="{{ $displayTitle }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,118,135,0.25),_transparent_42%),linear-gradient(180deg,_rgba(255,255,255,0.4),_rgba(15,61,46,0.1))]"></div>
                        @endif
                    </div>

                    <div class="absolute -bottom-5 left-4 rounded-[28px] border border-white/70 bg-white/90 px-5 py-4 shadow-float backdrop-blur sm:left-8">
                        <div class="text-xs font-semibold uppercase tracking-[0.22em] text-pine-700">Advocacy Focus</div>
                        <div class="mt-2 text-sm font-medium text-slate-700">Climate resilience, environmental protection, and citizen action</div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
