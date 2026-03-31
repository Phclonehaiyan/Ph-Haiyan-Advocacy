@php
    $navigation = config('site.navigation');
@endphp

<header x-data="siteNav()" class="sticky top-0 z-40 border-b border-white/60 bg-white/80 backdrop-blur-xl">
    <nav class="section-shell flex items-center justify-between gap-3 py-4 sm:gap-4 sm:py-5 xl:gap-6">
        <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-3 pr-2 sm:gap-4 lg:flex-none lg:min-w-[300px] xl:min-w-[360px]">
            <img src="{{ asset('images/brand/ph-haiyan-logo.png') }}" alt="PH Haiyan Advocacy logo" class="h-12 w-auto shrink-0 object-contain sm:h-14">
            <div class="min-w-0">
                <div class="font-display text-[1.14rem] font-semibold leading-tight text-pine-950 sm:text-[1.35rem] lg:text-[1.55rem] lg:leading-none lg:whitespace-nowrap">PH Haiyan Advocacy Inc.</div>
                <div class="mt-1 hidden text-[0.6rem] uppercase tracking-[0.22em] text-slate-500 sm:block lg:text-[0.68rem] lg:tracking-[0.28em] lg:whitespace-nowrap">Climate resilience advocacy</div>
            </div>
        </a>

        <div class="hidden items-center gap-2 xl:gap-3 lg:flex">
            @foreach ($navigation as $item)
                @if (! empty($item['children']))
                    @php
                        $activeChild = collect($item['children'])->first(fn ($child) => request()->routeIs($child['route']));
                        $dropdownLabel = $activeChild['label'] ?? $item['label'];
                    @endphp
                    <div class="relative" @keydown.escape.window="openMenu = null">
                        <button
                            type="button"
                            class="nav-link inline-flex items-center gap-2 {{ $activeChild ? 'is-active' : '' }}"
                            @click="toggleMenu('{{ $loop->index }}')"
                            :aria-expanded="openMenu === '{{ $loop->index }}'"
                        >
                            <span>{{ $dropdownLabel }}</span>
                            <x-icon name="chevron-down" class="h-4 w-4" />
                        </button>

                        <div
                            x-cloak
                            x-show="openMenu === '{{ $loop->index }}'"
                            x-transition.origin.top.left
                            @click.outside="openMenu = null"
                            class="absolute left-0 top-full mt-3 min-w-64 rounded-3xl border border-pine-100 bg-white/95 p-3 shadow-float"
                        >
                            @foreach ($item['children'] as $child)
                                <a
                                    href="{{ route($child['route']) }}"
                                    class="group flex items-center justify-between rounded-2xl px-4 py-3 text-sm transition {{ request()->routeIs($child['route']) ? 'bg-pine-50 text-pine-900' : 'text-slate-600 hover:bg-pine-50 hover:text-pine-900' }}"
                                >
                                    <span>{{ $child['label'] }}</span>
                                    <x-icon name="arrow-up-right" class="h-4 w-4 text-pine-600/60 transition group-hover:text-pine-700" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>

        <div class="hidden items-center lg:flex">
            <form
                action="{{ route('search.index') }}"
                method="GET"
                class="group relative transition-all duration-300 ease-out"
                :class="searchCompressed && !searchFocused ? 'w-[210px] xl:w-[230px] 2xl:w-[250px]' : 'w-[250px] xl:w-[280px] 2xl:w-[320px]'"
            >
                <x-icon
                    name="search"
                    class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 transition-all duration-300 ease-out group-focus-within:text-pine-700"
                    ::class="searchCompressed && !searchFocused ? 'left-4' : 'left-5'"
                />
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search the website"
                    class="h-12 w-full rounded-full border border-slate-200 bg-sand-50 text-sm text-slate-700 placeholder:text-slate-400 transition-all duration-300 ease-out focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100"
                    :class="searchCompressed && !searchFocused ? 'pl-10 pr-14' : 'pl-12 pr-16'"
                    @focus="searchFocused = true"
                    @blur="searchFocused = false"
                >
                <button
                    type="submit"
                    class="absolute right-1.5 top-1/2 inline-flex items-center justify-center rounded-full bg-pine-900 text-xs font-semibold uppercase tracking-[0.18em] text-white transition-all duration-300 ease-out hover:bg-pine-800 -translate-y-1/2"
                    :class="searchCompressed && !searchFocused ? 'h-8 px-3' : 'h-9 px-4'"
                    aria-label="Search the website"
                >
                    Go
                </button>
            </form>
        </div>

        <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 text-slate-700 lg:hidden" @click="mobileOpen = ! mobileOpen" :aria-expanded="mobileOpen">
            <x-icon name="menu" class="h-5 w-5" x-show="! mobileOpen" x-cloak />
            <x-icon name="close" class="h-5 w-5" x-show="mobileOpen" x-cloak />
        </button>
    </nav>

        <div x-cloak x-show="mobileOpen" x-transition.origin.top class="border-t border-slate-200 bg-white/95 lg:hidden">
        <div class="section-shell space-y-3 py-5">
            <form action="{{ route('search.index') }}" method="GET" class="relative mb-2">
                <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search the website"
                    class="h-12 w-full rounded-3xl border border-slate-200 bg-sand-50 pl-11 pr-24 text-sm text-slate-700 placeholder:text-slate-400 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100"
                >
                <button type="submit" class="absolute right-1.5 top-1/2 inline-flex h-9 items-center justify-center rounded-full bg-pine-900 px-4 text-xs font-semibold uppercase tracking-[0.18em] text-white -translate-y-1/2">
                    Search
                </button>
            </form>

            @foreach ($navigation as $item)
                @if (! empty($item['children']))
                    @php
                        $activeChild = collect($item['children'])->first(fn ($child) => request()->routeIs($child['route']));
                        $dropdownLabel = $activeChild['label'] ?? $item['label'];
                    @endphp
                    <div class="rounded-3xl border border-slate-200/80 p-3">
                        <button type="button" class="flex w-full items-center justify-between text-left text-sm font-semibold uppercase tracking-[0.22em] {{ $activeChild ? 'text-pine-900' : 'text-slate-600' }}" @click="toggleMenu('mobile-{{ $loop->index }}')">
                            <span>{{ $dropdownLabel }}</span>
                            <x-icon name="chevron-down" class="h-4 w-4 transition" ::class="{ 'rotate-180': openMenu === 'mobile-{{ $loop->index }}' }" />
                        </button>

                        <div x-cloak x-show="openMenu === 'mobile-{{ $loop->index }}'" class="mt-3 space-y-2">
                            @foreach ($item['children'] as $child)
                                <a href="{{ route($child['route']) }}" class="block rounded-2xl px-4 py-3 text-sm {{ request()->routeIs($child['route']) ? 'bg-pine-50 text-pine-900' : 'bg-sand-50 text-slate-700' }}">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route($item['route']) }}" class="block rounded-3xl border border-slate-200/80 px-4 py-4 text-sm font-semibold uppercase tracking-[0.22em] text-slate-700">
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach

        </div>
    </div>
</header>
