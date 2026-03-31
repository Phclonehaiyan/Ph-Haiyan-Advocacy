@php
    $contact = config('site.contact');
    $socials = config('site.socials');
@endphp

<div class="relative overflow-hidden border-b border-white/8 bg-pine-950 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_left_top,rgba(255,212,77,0.12),transparent_28%),radial-gradient(circle_at_right_bottom,rgba(255,255,255,0.06),transparent_24%)]"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-white/18 to-transparent"></div>

    <div class="section-shell relative flex flex-col gap-3 py-3 text-sm text-white/72 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-center md:justify-start">
            <a href="mailto:{{ $contact['email'] }}" class="inline-flex items-center gap-2 transition duration-300 hover:text-white">
                <x-icon name="mail" class="h-4 w-4 text-[#ffd44d]" />
                <span>{{ $contact['email'] }}</span>
            </a>
            <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="inline-flex items-center gap-2 transition duration-300 hover:text-white">
                <x-icon name="phone" class="h-4 w-4 text-[#ffd44d]" />
                <span>{{ $contact['phone'] }}</span>
            </a>
            <span class="hidden items-center gap-2 lg:inline-flex">
                <x-icon name="map-pin" class="h-4 w-4 text-[#ffd44d]" />
                <span>{{ $contact['location'] }}</span>
            </span>
        </div>

        <div class="flex items-center justify-center gap-2 sm:gap-3 md:justify-end">
            @foreach ($socials as $social)
                @php
                    $iconName = \Illuminate\Support\Str::lower($social['label']);
                @endphp
                <a
                    href="{{ $social['href'] }}"
                    target="_blank"
                    rel="noreferrer"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/68 transition duration-300 hover:-translate-y-0.5 hover:border-white/22 hover:bg-white/10 hover:text-white"
                    aria-label="{{ $social['label'] }}"
                >
                    <x-icon :name="$iconName" class="h-4 w-4" />
                </a>
            @endforeach

            <a href="{{ route('support') }}" class="hidden items-center gap-2 rounded-full bg-[#ffd44d] px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.22em] text-pine-950 shadow-[0_12px_28px_rgba(255,212,77,0.18)] transition duration-300 hover:-translate-y-0.5 hover:bg-[#ffdd69] sm:inline-flex">
                <x-icon name="heart" class="h-3.5 w-3.5" />
                <span>Support the Mission</span>
            </a>
        </div>
    </div>
</div>
