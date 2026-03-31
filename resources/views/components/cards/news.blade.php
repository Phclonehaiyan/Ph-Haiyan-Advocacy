@props([
    'post',
    'featured' => false,
])

<article id="news-{{ $post->slug }}" class="surface-card {{ $featured ? 'overflow-hidden p-0 lg:grid lg:grid-cols-[1.05fr_0.95fr]' : 'h-full' }}">
    @if ($featured)
        <a href="{{ route('news.show', $post) }}" class="block overflow-hidden rounded-[28px] transition lg:rounded-r-none">
            <img src="{{ asset(ltrim($post->image, '/')) }}" alt="{{ $post->image_alt ?: $post->title }}" loading="lazy" decoding="async" class="h-full min-h-80 w-full object-cover transition duration-500 hover:scale-[1.02]">
        </a>
        <div class="p-8 lg:p-10">
            <div class="flex flex-wrap items-center gap-3">
                <span class="chip">{{ $post->category }}</span>
                <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $post->published_at?->format('M d, Y') }}</span>
            </div>
            <h3 class="mt-6 font-display text-3xl font-semibold text-pine-950">
                <a href="{{ route('news.show', $post) }}" class="transition hover:text-pine-800">
                    {{ $post->title }}
                </a>
            </h3>
            <p class="mt-4 text-base leading-8 text-slate-600">{{ $post->excerpt }}</p>
            <a href="{{ route('news.show', $post) }}" class="btn-secondary mt-8">
                Read full story
            </a>
        </div>
    @else
        <a href="{{ route('news.show', $post) }}" class="block overflow-hidden rounded-[28px]">
            <img src="{{ asset(ltrim($post->image, '/')) }}" alt="{{ $post->image_alt ?: $post->title }}" loading="lazy" decoding="async" class="h-48 w-full rounded-[28px] object-cover transition duration-500 hover:scale-[1.03]">
        </a>
        <div class="mt-6 flex items-center justify-between gap-4">
            <span class="chip">{{ $post->category }}</span>
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $post->published_at?->format('M d, Y') }}</span>
        </div>
        <h3 class="mt-4 text-xl font-semibold text-pine-950">
            <a href="{{ route('news.show', $post) }}" class="transition hover:text-pine-800">
                {{ $post->title }}
            </a>
        </h3>
        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $post->excerpt }}</p>
        <a href="{{ route('news.show', $post) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-pine-800 transition hover:text-pine-950">
            Read story
            <x-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
    @endif
</article>
