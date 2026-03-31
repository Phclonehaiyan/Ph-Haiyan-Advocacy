@props(['letter'])

<article id="letter-{{ $letter->slug }}" class="surface-card h-full">
    <div class="flex flex-wrap items-center gap-3">
        <span class="chip">{{ $letter->category }}</span>
        <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $letter->published_at?->format('M d, Y') }}</span>
    </div>
    <h3 class="mt-5 text-xl font-semibold text-pine-950">{{ $letter->title }}</h3>
    <div class="mt-3 text-sm font-medium uppercase tracking-[0.18em] text-pine-700">{{ $letter->topic }}</div>
    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $letter->summary }}</p>
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ $letter->document_url ?: '#' }}" target="_blank" rel="noreferrer" class="btn-secondary">
            View document
        </a>
        <a href="{{ route('letters.index') }}#letter-{{ $letter->slug }}" class="inline-flex items-center gap-2 text-sm font-semibold text-pine-800 transition hover:text-pine-950">
            Open archive entry
            <x-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
    </div>
</article>
