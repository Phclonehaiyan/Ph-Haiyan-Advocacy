@props(['topic'])

<article class="surface-card h-full">
    <div class="flex flex-wrap items-center gap-3">
        <span class="chip">{{ $topic->category }}</span>
        @if ($topic->is_pinned)
            <span class="chip bg-pine-50 text-pine-700 ring-pine-100">Pinned</span>
        @endif
        @if ($topic->is_featured)
            <span class="chip bg-teal-50 text-teal-700 ring-teal-100">Featured</span>
        @endif
    </div>

    <h3 class="mt-5 text-xl font-semibold text-pine-950">{{ $topic->title }}</h3>
    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $topic->summary }}</p>

    @if (! empty($topic->tags))
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($topic->tags as $tag)
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">#{{ $tag }}</span>
            @endforeach
        </div>
    @endif

    <div class="mt-6 grid grid-cols-3 gap-3 rounded-[24px] border border-slate-100 bg-slate-50/80 p-4 text-center">
        <div>
            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Replies</div>
            <div class="mt-2 text-lg font-semibold text-pine-950">{{ $topic->replies_count }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Views</div>
            <div class="mt-2 text-lg font-semibold text-pine-950">{{ $topic->views_count }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Status</div>
            <div class="mt-2 text-lg font-semibold capitalize text-pine-950">{{ $topic->status }}</div>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-between gap-4 text-sm text-slate-500">
        <span>Started by {{ $topic->starter_name }}</span>
        <span>{{ $topic->last_activity_at?->diffForHumans() }}</span>
    </div>
</article>
