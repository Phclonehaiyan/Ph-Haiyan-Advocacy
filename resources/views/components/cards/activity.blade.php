@props(['activity'])

<article class="surface-card h-full overflow-hidden p-0">
    <div class="overflow-hidden rounded-[28px]">
        <img src="{{ asset(ltrim($activity->image, '/')) }}" alt="{{ $activity->image_alt ?: $activity->title }}" loading="lazy" decoding="async" class="h-56 w-full rounded-[28px] object-cover transition duration-700 hover:scale-[1.03]">
    </div>
    <div class="px-2 pt-6">
        <div class="flex flex-wrap items-center gap-3">
            <span class="chip">{{ $activity->category }}</span>
            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $activity->activity_date?->format('M d, Y') }}</span>
        </div>
        <h3 class="mt-4 text-xl font-semibold text-pine-950">{{ $activity->title }}</h3>
        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $activity->summary }}</p>
        @if ($activity->location)
            <div class="mt-5 inline-flex items-center gap-2 text-sm text-slate-500">
                <x-icon name="map-pin" class="h-4 w-4 text-pine-700" />
                <span>{{ $activity->location }}</span>
            </div>
        @endif
    </div>
</article>
