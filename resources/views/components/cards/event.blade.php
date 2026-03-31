@props(['event'])

<article id="event-{{ $event->slug }}" class="scroll-mt-28 overflow-hidden rounded-[32px] border border-white/80 bg-white/94 shadow-soft">
    <div class="grid gap-0 lg:grid-cols-[260px_1fr]">
        @if ($event->image)
            <div class="overflow-hidden">
                <img src="{{ asset(ltrim($event->image, '/')) }}" alt="{{ $event->image_alt ?: $event->title }}" loading="lazy" decoding="async" class="h-full min-h-72 w-full object-cover">
            </div>
        @endif

        <div class="p-6 sm:p-7 lg:p-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="chip">{{ $event->category }}</span>
                @if ($event->is_featured)
                    <span class="chip bg-teal-50 text-teal-700 ring-teal-100">Archive Highlight</span>
                @endif
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $event->start_at?->format('M d, Y') }}</span>
            </div>

            <h3 class="mt-4 max-w-4xl text-2xl font-semibold leading-tight text-pine-950 sm:text-[2rem]">
                {{ $event->title }}
            </h3>

            <p class="mt-4 max-w-4xl text-base leading-8 text-slate-600">
                {{ $event->summary }}
            </p>

            <div class="mt-6 grid gap-3 text-sm text-slate-500 sm:grid-cols-2">
                <div class="flex items-center gap-3 rounded-[20px] border border-slate-200/80 bg-slate-50/80 px-4 py-3">
                    <x-icon name="calendar" class="h-4 w-4 text-pine-700" />
                    <span>{{ $event->start_at?->format('F d, Y | g:i A') }}</span>
                </div>
                <div class="flex items-center gap-3 rounded-[20px] border border-slate-200/80 bg-slate-50/80 px-4 py-3">
                    <x-icon name="map-pin" class="h-4 w-4 text-pine-700" />
                    <span>{{ $event->venue ? $event->venue.', ' : '' }}{{ $event->location }}</span>
                </div>
            </div>

            <div class="mt-6 rounded-[24px] border border-slate-200/80 bg-[linear-gradient(135deg,_rgba(248,250,252,0.95),_rgba(255,255,255,0.9))] px-5 py-5">
                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">
                    Event details
                </div>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    {{ $event->description }}
                </p>
            </div>
        </div>
    </div>
</article>
