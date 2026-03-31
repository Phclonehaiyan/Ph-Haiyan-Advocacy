@php
    $contact = config('site.contact');
    $socials = config('site.socials');
    $links = config('site.quick_links');
    $footer = config('site.footer');
    $mapLink = 'https://www.google.com/maps/search/?api=1&query='.urlencode($contact['address']);
    $quickLinkIcons = [
        'home' => 'spark',
        'about' => 'eye',
        'what-we-do' => 'sprout',
        'contact.index' => 'mail',
    ];
@endphp

<footer class="mt-24 border-t border-white/6 bg-pine-950 text-white">
    <div class="relative overflow-hidden">
        <div
            class="pointer-events-none absolute inset-0 opacity-40"
            style="
                background-image:
                    linear-gradient(90deg, rgba(3, 33, 24, 0.96) 0%, rgba(3, 33, 24, 0.92) 36%, rgba(3, 33, 24, 0.88) 62%, rgba(3, 33, 24, 0.95) 100%),
                    radial-gradient(circle at 15% 30%, rgba(255, 212, 77, 0.10), transparent 22%),
                    radial-gradient(circle at 78% 22%, rgba(94, 234, 212, 0.08), transparent 24%),
                    url('{{ asset('images/hero/mangrove-pexels-wide.jpg') }}');
                background-position: center, left top, right top, center center;
                background-size: cover, auto, auto, cover;
            "
        ></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-[linear-gradient(180deg,_rgba(255,255,255,0.05)_0%,_rgba(255,255,255,0)_100%)]"></div>
        <div class="pointer-events-none absolute inset-y-0 left-0 w-[38%] bg-[radial-gradient(circle_at_left,_rgba(255,255,255,0.06),_transparent_62%)]"></div>

        <div class="section-shell relative py-14 lg:py-16">
            <div class="grid gap-10 lg:grid-cols-[1fr_0.72fr_0.95fr]">
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/brand/ph-haiyan-logo.png') }}" alt="PH Haiyan Advocacy logo" class="h-14 w-auto shrink-0 object-contain">
                    <div>
                        <div class="font-display text-[1.55rem] font-semibold">PH Haiyan Advocacy Inc.</div>
                        <div class="mt-1 text-sm text-white/58">{{ $contact['location'] }}</div>
                    </div>
                </div>

                <div class="h-px w-16 bg-white/10"></div>

                <p class="max-w-sm text-sm leading-7 text-white/62">
                    {{ $footer['donate_note'] }}
                </p>

                <a href="{{ route('support') }}" class="inline-flex items-center gap-2 rounded-full bg-[#ffd44d] px-5 py-3 text-sm font-semibold text-pine-950 transition hover:bg-[#ffdd69]">
                    <x-icon name="heart" class="h-4 w-4" />
                    <span>Support the Mission</span>
                </a>
            </div>

            <div class="space-y-8 lg:border-l lg:border-white/6 lg:pl-10">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.28em] text-white/45">Quick Links</div>
                    <div class="mt-6 flex flex-col gap-4">
                        @foreach ($links as $link)
                            @php
                                $icon = $quickLinkIcons[$link['route']] ?? 'arrow-up-right';
                            @endphp
                            <a href="{{ route($link['route']) }}" class="group flex items-center gap-3 text-sm font-medium text-white/72 transition hover:text-white">
                                <x-icon :name="$icon" class="h-4 w-4 text-white/40 transition group-hover:text-[#ffd44d]" />
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.28em] text-white/45">Follow Us</div>
                    <div class="mt-4 flex flex-wrap gap-3">
                        @foreach ($socials as $social)
                            @php
                                $iconName = \Illuminate\Support\Str::lower($social['label']);
                            @endphp
                            <a
                                href="{{ $social['href'] }}"
                                target="_blank"
                                rel="noreferrer"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/68 transition hover:border-white/22 hover:bg-white/10 hover:text-white"
                                aria-label="{{ $social['label'] }}"
                            >
                                <x-icon :name="$iconName" class="h-4 w-4" />
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:border-l lg:border-white/6 lg:pl-10">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.28em] text-white/45">Contact</div>
                    <div class="mt-6 space-y-4 text-sm text-white/72">
                        <a href="mailto:{{ $contact['email'] }}" class="flex items-start gap-3 transition hover:text-white">
                            <x-icon name="mail" class="mt-0.5 h-4 w-4 text-white/55" />
                            <span>{{ $contact['email'] }}</span>
                        </a>
                        <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="flex items-start gap-3 transition hover:text-white">
                            <x-icon name="phone" class="mt-0.5 h-4 w-4 text-white/55" />
                            <span>{{ $contact['phone'] }}</span>
                        </a>
                        <div class="flex items-start gap-3">
                            <x-icon name="map-pin" class="mt-0.5 h-4 w-4 text-white/55" />
                            <span>{{ $contact['address'] }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ $mapLink }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-white/62 transition hover:text-white">
                    <x-icon name="arrow-up-right" class="h-4 w-4" />
                    <span>Open location in Google Maps</span>
                </a>
            </div>
            </div>
        </div>
    </div>

    <div class="border-t border-white/6">
        <div class="section-shell flex flex-col gap-4 py-5 text-sm text-white/45 sm:flex-row sm:items-center sm:justify-between">
            <div>&copy; {{ now()->year }} PH Haiyan Advocacy Inc. All rights reserved.</div>
            <div>{{ $footer['trust_line'] }}</div>
        </div>
    </div>
</footer>
