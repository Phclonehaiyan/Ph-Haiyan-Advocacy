@extends('layouts.app', [
    'pageTitle' => $post->meta_title ?: ($post->title . ' | News | PH Haiyan Advocacy Inc.'),
    'pageDescription' => $post->meta_description ?: $post->excerpt,
    'seo' => [
        'canonical_url' => route('news.show', $post),
        'image' => $post->og_image ?: $post->image,
        'type' => 'article',
    ],
])

@section('content')
    @php
        use Illuminate\Support\Str;

        $storyHeadings = [];
        $storyHeadingCounts = [];
        $storyBody = preg_replace_callback('/<h([23])>(.*?)<\/h\1>/i', function (array $matches) use (&$storyHeadings, &$storyHeadingCounts) {
            $title = trim(strip_tags(html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            $baseId = Str::slug($title) ?: 'news-section';
            $storyHeadingCounts[$baseId] = ($storyHeadingCounts[$baseId] ?? 0) + 1;
            $id = $storyHeadingCounts[$baseId] > 1 ? $baseId . '-' . $storyHeadingCounts[$baseId] : $baseId;

            $storyHeadings[] = [
                'id' => $id,
                'title' => $title,
                'level' => (int) $matches[1],
            ];

            return '<h' . $matches[1] . ' id="' . e($id) . '">' . $matches[2] . '</h' . $matches[1] . '>';
        }, $post->content ?? '') ?: ($post->content ?? '');

        $normalizeHeading = static fn (string $value): string => Str::of(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->trim()
            ->value();

        if (! empty($storyHeadings) && ($storyHeadings[0]['level'] ?? null) === 2) {
            $firstHeadingTitle = $normalizeHeading($storyHeadings[0]['title']);
            $postTitle = $normalizeHeading($post->title);

            if ($firstHeadingTitle !== '' && $postTitle !== '' && (Str::contains($firstHeadingTitle, $postTitle) || Str::contains($postTitle, $firstHeadingTitle))) {
                $storyBody = preg_replace('/^\s*<h2\b[^>]*>.*?<\/h2>\s*/is', '', $storyBody, 1) ?: $storyBody;
                array_shift($storyHeadings);
            }
        }

        $storyLead = $storyHeadings[0]['title'] ?? 'Story overview';
        $galleryViewerImages = collect([
            [
                'src' => asset(ltrim($post->image, '/')),
                'alt' => $post->image_alt ?: $post->title,
                'caption' => $post->title,
            ],
            ...$post->galleryImages->map(fn ($galleryImage) => [
                'src' => asset(ltrim($galleryImage->image, '/')),
                'alt' => $galleryImage->image_alt ?: $post->title,
                'caption' => $galleryImage->caption ?: $post->title,
            ])->all(),
        ])->values();
    @endphp

    <div
        x-data="{
            images: @js($galleryViewerImages),
            activeIndex: null,
            touchStartX: null,
            get activeImage() {
                return this.activeIndex === null ? null : this.images[this.activeIndex];
            },
            openImage(index) {
                this.activeIndex = index;
                document.body.classList.add('overflow-hidden');
            },
            closeImage() {
                this.activeIndex = null;
                document.body.classList.remove('overflow-hidden');
            },
            previousImage() {
                if (this.activeIndex === null || this.images.length <= 1) return;
                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
            },
            nextImage() {
                if (this.activeIndex === null || this.images.length <= 1) return;
                this.activeIndex = (this.activeIndex + 1) % this.images.length;
            },
            handleTouchStart(event) {
                this.touchStartX = event.touches[0]?.clientX ?? null;
            },
            handleTouchEnd(event) {
                if (this.touchStartX === null) return;

                const touchEndX = event.changedTouches[0]?.clientX ?? null;

                if (touchEndX === null) {
                    this.touchStartX = null;
                    return;
                }

                const delta = touchEndX - this.touchStartX;

                if (Math.abs(delta) > 40) {
                    if (delta > 0) {
                        this.previousImage();
                    } else {
                        this.nextImage();
                    }
                }

                this.touchStartX = null;
            }
        }"
        @keydown.escape.window="closeImage()"
        @keydown.arrow-left.window="if (activeImage) previousImage()"
        @keydown.arrow-right.window="if (activeImage) nextImage()"
    >
    <section class="section-shell py-12 lg:py-16">
        <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 rounded-full border border-pine-100 bg-white px-4 py-2 text-sm font-semibold text-pine-900 shadow-soft transition hover:border-pine-200 hover:bg-pine-50">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Back to updates
        </a>

        <article class="mt-8 overflow-hidden rounded-[36px] border border-white/80 bg-white/94 shadow-soft">
            <div class="grid gap-0 xl:grid-cols-[0.96fr_1.04fr]">
                <div class="bg-white p-6 lg:p-8">
                    <button
                        type="button"
                        class="block w-full overflow-hidden rounded-[30px] border border-slate-100 bg-slate-50 text-left shadow-[0_18px_42px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5"
                        @click="openImage(0)"
                    >
                        <img src="{{ asset(ltrim($post->image, '/')) }}" alt="{{ $post->image_alt ?: $post->title }}" class="aspect-[5/4] w-full object-cover">
                    </button>
                    <p class="mt-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Tap image to view larger</p>
                </div>

                <div class="border-t border-slate-100 bg-[linear-gradient(180deg,rgba(248,250,249,0.92),rgba(255,255,255,1))] p-8 lg:p-10 xl:border-l xl:border-t-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="chip">{{ $post->category }}</span>
                        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                            <x-icon name="calendar" class="h-4 w-4" />
                            {{ $post->published_at?->format('M d, Y') }}
                        </span>
                        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                            <x-icon name="clock" class="h-4 w-4" />
                            {{ $post->reading_time }} min read
                        </span>
                    </div>

                    <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-pine-100 bg-pine-50 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-pine-700">
                        <span class="inline-block h-2 w-2 rounded-full bg-pine-600"></span>
                        Archived PH Haiyan update
                    </div>

                    <h1 class="mt-6 font-display text-4xl font-semibold leading-[1.02] tracking-tight text-pine-950 lg:text-[3.35rem]">
                        {{ $post->title }}
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-600">
                        {{ $post->excerpt }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="#story-body" class="btn-primary gap-2">
                            <x-icon name="eye" class="h-4 w-4" />
                            Read full story
                        </a>
                        <a href="{{ route('news.index') }}" class="btn-secondary">
                            Back to news archive
                        </a>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[22px] border border-slate-200/80 bg-white/88 px-4 py-4 shadow-soft">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Category</div>
                            <div class="mt-2 text-base font-semibold text-pine-950">{{ $post->category }}</div>
                        </div>
                        <div class="rounded-[22px] border border-slate-200/80 bg-white/88 px-4 py-4 shadow-soft">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Published</div>
                            <div class="mt-2 text-base font-semibold text-pine-950">{{ $post->published_at?->format('F d, Y') }}</div>
                        </div>
                        <div class="rounded-[22px] border border-slate-200/80 bg-white/88 px-4 py-4 shadow-soft">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Story Lead</div>
                            <div class="mt-2 text-base font-semibold text-pine-950">{{ $storyLead }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="section-shell py-8 lg:py-10">
        <div id="story-body" class="mx-auto max-w-6xl rounded-[36px] border border-white/80 bg-white/94 px-6 py-8 shadow-soft sm:px-8 lg:px-10 lg:py-12">
            <div class="grid gap-8 xl:grid-cols-[17.5rem_minmax(0,1fr)]">
                <aside class="space-y-5 xl:sticky xl:top-28 xl:self-start">
                    <div class="rounded-[28px] border border-pine-100 bg-slate-50/90 p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">In This Story</div>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            A preserved article-style update rebuilt from PH Haiyan's archived public website materials.
                        </p>

                        @if (! empty($storyHeadings))
                            <nav class="mt-5 space-y-2">
                                @foreach ($storyHeadings as $heading)
                                    <a
                                        href="#{{ $heading['id'] }}"
                                        class="block rounded-2xl border border-transparent px-3 py-2 text-sm leading-6 text-slate-600 transition hover:border-pine-100 hover:bg-white hover:text-pine-950 {{ $heading['level'] === 3 ? 'ml-3 text-[13px]' : 'font-semibold' }}"
                                    >
                                        {{ $heading['title'] }}
                                    </a>
                                @endforeach
                            </nav>
                        @endif
                    </div>

                    <div class="rounded-[28px] border border-pine-100 bg-white p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Archive Note</div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            This page preserves the full story context behind the update so the archive remains readable even after the old website is retired.
                        </p>
                    </div>
                </aside>

                <div class="rounded-[32px] border border-slate-100 bg-white px-6 py-7 shadow-soft sm:px-8 sm:py-8">
                    <div class="mb-8 flex flex-wrap items-center gap-3 border-b border-pine-100 pb-6">
                        <span class="chip">News Story</span>
                        <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                            Context, chronology, and public-facing explanation preserved in a fuller article flow
                        </span>
                    </div>

                    <div class="story-content [&_section]:mt-10 [&_section]:border-t [&_section]:border-pine-100 [&_section]:pt-10 [&_section:first-child]:mt-0 [&_section:first-child]:border-t-0 [&_section:first-child]:pt-0 [&_h2]:scroll-mt-28 [&_h2]:font-display [&_h2]:text-3xl [&_h2]:font-semibold [&_h2]:leading-[1.08] [&_h2]:text-pine-950 [&_h3]:mt-8 [&_h3]:scroll-mt-28 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-pine-900 [&_p]:mt-4 [&_p]:text-base [&_p]:leading-8 [&_p]:text-slate-600 [&_ul]:mt-4 [&_ul]:space-y-3 [&_ul]:pl-6 [&_ul]:text-base [&_ul]:leading-8 [&_ul]:text-slate-600 [&_ol]:mt-4 [&_ol]:space-y-3 [&_ol]:pl-6 [&_ol]:text-base [&_ol]:leading-8 [&_ol]:text-slate-600 [&_blockquote]:mt-6 [&_blockquote]:rounded-[24px] [&_blockquote]:border-l-4 [&_blockquote]:border-pine-300 [&_blockquote]:bg-pine-50/70 [&_blockquote]:px-5 [&_blockquote]:py-4 [&_blockquote]:text-lg [&_blockquote]:leading-8 [&_blockquote]:text-pine-900 [&_a]:font-semibold [&_a]:text-pine-800 hover:[&_a]:text-pine-950">
                        {!! $storyBody !!}
                    </div>

                    @if ($post->galleryImages->isNotEmpty())
                        <div class="mt-12 border-t border-pine-100 pt-10">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Story gallery</div>
                                    <h2 class="mt-3 font-display text-3xl font-semibold text-pine-950">Supporting images from the field and archive.</h2>
                                </div>
                                <div class="text-sm text-slate-500">{{ $post->galleryImages->count() }} additional image{{ $post->galleryImages->count() > 1 ? 's' : '' }}</div>
                            </div>

                            <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($post->galleryImages as $galleryImage)
                                    <figure class="overflow-hidden rounded-[28px] border border-slate-100 bg-[linear-gradient(180deg,rgba(248,250,249,0.94),rgba(255,255,255,1))] shadow-soft">
                                        <button
                                            type="button"
                                            class="group block w-full text-left transition hover:opacity-95"
                                            @click="openImage({{ $loop->index + 1 }})"
                                        >
                                            <img
                                                src="{{ asset(ltrim($galleryImage->image, '/')) }}"
                                                alt="{{ $galleryImage->image_alt ?: $post->title }}"
                                                loading="lazy"
                                                decoding="async"
                                                class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                                            >

                                            <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-white px-5 py-4 text-sm">
                                                <div class="font-medium text-pine-950">Open image</div>
                                                <div class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Tap to zoom</div>
                                            </div>
                                        </button>
                                    </figure>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($relatedPosts->isNotEmpty())
        <section class="section-shell py-8 lg:py-10">
            <x-section-header
                eyebrow="Related Updates"
                title="Continue through connected public updates."
                description="These items are tied to the same advocacy theme, policy issue, or field activity context."
            />

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @foreach ($relatedPosts as $relatedPost)
                    <article class="surface-card h-full">
                        <div class="overflow-hidden rounded-[24px]">
                            <img src="{{ asset(ltrim($relatedPost->image, '/')) }}" alt="{{ $relatedPost->image_alt ?: $relatedPost->title }}" loading="lazy" decoding="async" class="aspect-[5/3] w-full object-cover">
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <span class="chip">{{ $relatedPost->category }}</span>
                            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $relatedPost->published_at?->format('M d, Y') }}</span>
                        </div>

                        <h2 class="mt-4 font-display text-2xl font-semibold leading-[1.08] text-pine-950">{{ $relatedPost->title }}</h2>
                        <p class="mt-4 text-sm leading-8 text-slate-600">{{ $relatedPost->excerpt }}</p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('news.show', $relatedPost) }}" class="btn-primary gap-2">
                                <x-icon name="eye" class="h-4 w-4" />
                                Read story
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
        <div
            x-cloak
            x-show="activeImage"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/88 p-4 backdrop-blur-sm"
            @click.self="closeImage()"
        >
            <div
                x-show="activeImage"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-5xl"
            >
                <button
                    type="button"
                    class="absolute right-3 top-3 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-950/70 text-white transition hover:bg-slate-950"
                    @click="closeImage()"
                    aria-label="Close image viewer"
                >
                    <x-icon name="close" class="h-5 w-5" />
                </button>

                <template x-if="images.length > 1">
                    <div>
                        <button
                            type="button"
                            class="absolute left-3 top-1/2 z-10 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-950/70 text-white transition hover:bg-slate-950"
                            @click="previousImage()"
                            aria-label="Previous image"
                        >
                            <x-icon name="arrow-left" class="h-5 w-5" />
                        </button>

                        <button
                            type="button"
                            class="absolute right-3 top-1/2 z-10 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-950/70 text-white transition hover:bg-slate-950"
                            @click="nextImage()"
                            aria-label="Next image"
                        >
                            <x-icon name="arrow-left" class="h-5 w-5 rotate-180" />
                        </button>
                    </div>
                </template>

                <div
                    class="overflow-hidden rounded-[28px] border border-white/10 bg-slate-950 shadow-[0_28px_80px_rgba(0,0,0,0.45)]"
                    @touchstart="handleTouchStart($event)"
                    @touchend="handleTouchEnd($event)"
                >
                    <img
                        :src="activeImage?.src"
                        :alt="activeImage?.alt || 'News image'"
                        class="max-h-[78vh] w-full object-contain bg-slate-950"
                    >

                    <div x-show="images.length > 1" class="border-t border-white/10 bg-slate-900/92 px-5 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-white/55">
                        <span x-text="`${(activeIndex ?? 0) + 1} of ${images.length}`"></span>
                        <span class="ml-3 text-white/35">Swipe or use arrows</span>
                    </div>
                    <div class="border-t border-white/10 bg-slate-900/96 px-5 py-4 text-sm leading-7 text-white/80" x-text="activeImage?.caption || activeImage?.alt || 'News image'"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
