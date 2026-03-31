@props(['video'])

<article class="surface-card h-full overflow-hidden p-0">
    <a href="{{ $video->video_url ?? '#' }}" target="_blank" rel="noreferrer" aria-label="Play {{ $video->title }} on Facebook" class="group relative block overflow-hidden rounded-[28px]">
        <img src="{{ asset(ltrim($video->thumbnail, '/')) }}" alt="{{ $video->title }}" loading="lazy" decoding="async" class="h-56 w-full rounded-[28px] object-cover transition duration-700 group-hover:scale-[1.03]">
        <div class="absolute inset-0 bg-gradient-to-t from-pine-950/70 via-pine-950/10 to-transparent"></div>
        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white text-pine-900 shadow-soft">
                <x-icon name="play" class="h-4 w-4" />
            </span>
            <div class="flex flex-wrap justify-end gap-2">
                @if ($video->platform)
                    <div class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white backdrop-blur">
                        {{ $video->platform }}
                    </div>
                @endif
                @if ($video->view_count_label)
                    <div class="rounded-full bg-pine-950/45 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white backdrop-blur">
                        {{ $video->view_count_label }}
                    </div>
                @elseif ($video->duration)
                    <div class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white backdrop-blur">
                        {{ $video->duration }}
                    </div>
                @endif
            </div>
        </div>
    </a>

    <div class="px-2 pt-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="chip">{{ $video->platform ?: 'Video Story' }}</div>
            @if ($video->view_count_label)
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $video->view_count_label }}</div>
            @endif
        </div>
        <h3 class="mt-4 text-xl font-semibold text-pine-950">{{ $video->title }}</h3>
        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $video->summary }}</p>
    </div>
</article>
