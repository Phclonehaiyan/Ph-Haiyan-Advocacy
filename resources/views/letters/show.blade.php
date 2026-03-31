@extends('layouts.app', [
    'pageTitle' => $letter->title . ' | Letters | PH Haiyan Advocacy Inc.',
    'pageDescription' => $letter->summary,
    'seo' => [
        'canonical_url' => route('letters.show', $letter),
        'image' => $letter->image,
    ],
])

@section('content')
    @php
        use Illuminate\Support\Str;

        $resolveMedia = static fn (?string $path): ?string => blank($path)
            ? null
            : (Str::startsWith($path, ['http://', 'https://']) ? $path : asset(ltrim($path, '/')));

        $attachments = collect($letter->attachments ?? []);

        $storyHeadings = [];
        $storyHeadingCounts = [];
        $storyBody = preg_replace_callback('/<h([23])>(.*?)<\/h\1>/i', function (array $matches) use (&$storyHeadings, &$storyHeadingCounts) {
            $title = trim(strip_tags(html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            $baseId = Str::slug($title) ?: 'story-section';
            $storyHeadingCounts[$baseId] = ($storyHeadingCounts[$baseId] ?? 0) + 1;
            $id = $storyHeadingCounts[$baseId] > 1 ? $baseId . '-' . $storyHeadingCounts[$baseId] : $baseId;

            $storyHeadings[] = [
                'id' => $id,
                'title' => $title,
                'level' => (int) $matches[1],
            ];

            return '<h' . $matches[1] . ' id="' . e($id) . '">' . $matches[2] . '</h' . $matches[1] . '>';
        }, $letter->body ?? '') ?: ($letter->body ?? '');

        $normalizeHeading = static fn (string $value): string => Str::of(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->trim()
            ->value();

        if (! empty($storyHeadings) && ($storyHeadings[0]['level'] ?? null) === 2) {
            $firstHeadingTitle = $normalizeHeading($storyHeadings[0]['title']);
            $letterTitle = $normalizeHeading($letter->title);

            if ($firstHeadingTitle !== '' && $letterTitle !== '' && (Str::contains($firstHeadingTitle, $letterTitle) || Str::contains($letterTitle, $firstHeadingTitle))) {
                $storyBody = preg_replace('/^\s*<h2\b[^>]*>.*?<\/h2>\s*/is', '', $storyBody, 1) ?: $storyBody;
                array_shift($storyHeadings);
            }
        }

        if ($letter->document_url && ! $attachments->contains(fn ($item) => ($item['url'] ?? null) === $letter->document_url)) {
            $attachments->prepend([
                'label' => 'Open official PDF',
                'url' => $letter->document_url,
            ]);
        }
    @endphp

    <section class="section-shell py-12 lg:py-16">
        <a href="{{ route('letters.index') }}" class="inline-flex items-center gap-2 rounded-full border border-pine-100 bg-white px-4 py-2 text-sm font-semibold text-pine-900 shadow-soft transition hover:border-pine-200 hover:bg-pine-50">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Back to letters
        </a>

        <div class="mt-8">
            <article class="overflow-hidden rounded-[36px] border border-white/80 bg-white/94 shadow-soft">
                <div class="grid xl:grid-cols-[minmax(0,1.03fr)_0.97fr]">
                    <div class="bg-white p-6 lg:p-8">
                        <div class="rounded-[32px] border border-slate-100 bg-slate-50/80 p-4 lg:p-5">
                            <div class="flex min-h-[340px] items-center justify-center rounded-[26px] bg-white">
                                <img src="{{ $resolveMedia($letter->image) }}" alt="{{ $letter->image_alt ?: $letter->title }}" class="max-h-[36rem] w-full object-contain object-top">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 bg-[linear-gradient(180deg,rgba(249,251,250,0.92),rgba(255,255,255,1))] p-8 lg:p-10 xl:border-l xl:border-t-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="chip">{{ $letter->category }}</span>
                            <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                                <x-icon name="calendar" class="h-4 w-4" />
                                {{ $letter->published_at?->format('M d, Y') }}
                            </span>
                        </div>

                        <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-pine-100 bg-pine-50 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-pine-700">
                            <span class="inline-block h-2 w-2 rounded-full bg-pine-600"></span>
                            Story-backed public record
                        </div>

                        <h1 class="mt-6 font-display text-4xl font-semibold leading-[1.02] text-pine-950 lg:text-[3.4rem]">
                            {{ $letter->title }}
                        </h1>

                        <div class="mt-4 text-sm font-semibold uppercase tracking-[0.2em] text-pine-700">
                            {{ $letter->topic }}
                        </div>

                        <p class="mt-5 max-w-2xl text-base leading-8 text-slate-600">
                            {{ $letter->summary }}
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="#record-narrative" class="btn-primary gap-2">
                                <x-icon name="eye" class="h-4 w-4" />
                                Read full story
                            </a>

                            <a href="{{ $letter->document_url ?: '#' }}" target="_blank" rel="noreferrer" class="btn-secondary gap-2">
                                <x-icon name="file-text" class="h-4 w-4" />
                                Open official PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 border-t border-slate-100 bg-slate-50/55 p-6 lg:p-8 xl:grid-cols-[minmax(0,1fr)_24rem]">
                    <div class="rounded-[28px] border border-white/80 bg-white/94 p-6 shadow-soft">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">At a Glance</div>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[20px] border border-slate-100 bg-slate-50/70 px-4 py-4">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Category</div>
                                <div class="mt-2 text-base font-semibold text-pine-950">{{ $letter->category }}</div>
                            </div>
                            <div class="rounded-[20px] border border-slate-100 bg-slate-50/70 px-4 py-4">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Issue Area</div>
                                <div class="mt-2 text-base font-semibold text-pine-950">{{ $letter->topic }}</div>
                            </div>
                            <div class="rounded-[20px] border border-slate-100 bg-slate-50/70 px-4 py-4">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Published</div>
                                <div class="mt-2 text-base font-semibold text-pine-950">{{ $letter->published_at?->format('F d, Y') }}</div>
                            </div>
                            <div class="rounded-[20px] border border-slate-100 bg-slate-50/70 px-4 py-4">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">Available Files</div>
                                <div class="mt-2 text-base font-semibold text-pine-950">{{ $attachments->count() }} record file{{ $attachments->count() !== 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div id="official-record-files" class="rounded-[28px] border border-white/80 bg-white/94 p-6 shadow-soft">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Official Record Files</div>
                        <div class="mt-5 space-y-3">
                            @foreach ($attachments as $attachment)
                                <a href="{{ $attachment['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="group flex items-center justify-between rounded-[22px] border border-pine-100 bg-slate-50/80 px-4 py-4 transition hover:border-pine-200 hover:bg-pine-50">
                                    <span class="flex min-w-0 items-center gap-3">
                                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-pine-700 ring-1 ring-pine-100">
                                            <x-icon name="file-text" class="h-4 w-4" />
                                        </span>
                                        <span class="min-w-0 text-sm font-semibold leading-6 text-pine-950">{{ $attachment['label'] ?? 'Open attachment' }}</span>
                                    </span>
                                    <x-icon name="arrow-up-right" class="h-4 w-4 shrink-0 text-pine-700 transition group-hover:text-pine-900" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>

    @if (! empty($letter->key_takeaways))
        <section class="section-shell py-4 lg:py-6">
            <div class="rounded-[32px] border border-amber-200 bg-amber-50/80 px-6 py-7 shadow-soft sm:px-8">
                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-800">Key Takeaways</div>
                <ul class="mt-5 space-y-3 text-sm leading-7 text-slate-700">
                    @foreach ($letter->key_takeaways as $point)
                        <li class="flex gap-3">
                            <span class="mt-2 inline-block h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>
                            <span>{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="section-shell py-8 lg:py-10">
        <div id="record-narrative" class="mx-auto max-w-6xl rounded-[36px] border border-white/80 bg-white/94 px-6 py-8 shadow-soft sm:px-8 lg:px-10 lg:py-12">
            <div class="grid gap-8 xl:grid-cols-[18rem_minmax(0,1fr)]">
                <aside class="space-y-5 xl:sticky xl:top-28 xl:self-start">
                    <div class="rounded-[28px] border border-pine-100 bg-slate-50/90 p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">In This Story</div>
                        <p class="mt-3 text-sm leading-7 text-slate-500">A preserved article-style narrative built from PH Haiyan’s original public record archive.</p>

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
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Story Format</div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            This page preserves the story, context, and official files tied to the letter, so the record remains readable even without the old website.
                        </p>
                    </div>
                </aside>

                <div>
                    <div class="mb-8 flex flex-wrap items-center gap-3 border-b border-pine-100 pb-6">
                        <span class="chip">Record Narrative</span>
                        <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Context, chronology, and public-interest details preserved in a fuller article flow</span>
                    </div>

                    <div class="story-content [&_section]:mt-10 [&_section]:border-t [&_section]:border-pine-100 [&_section]:pt-10 [&_section:first-child]:mt-0 [&_section:first-child]:border-t-0 [&_section:first-child]:pt-0 [&_h2]:scroll-mt-28 [&_h2]:font-display [&_h2]:text-3xl [&_h2]:font-semibold [&_h2]:leading-[1.08] [&_h2]:text-pine-950 [&_h3]:mt-8 [&_h3]:scroll-mt-28 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-pine-900 [&_p]:mt-4 [&_p]:text-base [&_p]:leading-8 [&_p]:text-slate-600 [&_ul]:mt-4 [&_ul]:space-y-3 [&_ul]:pl-6 [&_ul]:text-base [&_ul]:leading-8 [&_ul]:text-slate-600 [&_ol]:mt-4 [&_ol]:space-y-3 [&_ol]:pl-6 [&_ol]:text-base [&_ol]:leading-8 [&_ol]:text-slate-600">
                        {!! $storyBody !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedLetters->isNotEmpty())
        <section class="section-shell py-8 lg:py-10">
            <x-section-header
                eyebrow="Related Letters"
                title="Continue through connected public records."
                description="These records are tied to the same issue area or advocacy theme."
            />

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @foreach ($relatedLetters as $relatedLetter)
                    <article class="surface-card h-full">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="chip">{{ $relatedLetter->category }}</span>
                            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $relatedLetter->published_at?->format('M d, Y') }}</span>
                        </div>

                        <h2 class="mt-5 font-display text-2xl font-semibold leading-[1.08] text-pine-950">{{ $relatedLetter->title }}</h2>
                        <div class="mt-3 text-sm font-semibold uppercase tracking-[0.18em] text-pine-700">{{ $relatedLetter->topic }}</div>
                        <p class="mt-4 text-sm leading-8 text-slate-600">{{ $relatedLetter->summary }}</p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('letters.show', $relatedLetter) }}" class="btn-primary gap-2">
                                <x-icon name="eye" class="h-4 w-4" />
                                Read story
                            </a>
                            <a href="{{ $relatedLetter->document_url ?: '#' }}" target="_blank" rel="noreferrer" class="btn-secondary gap-2">
                                <x-icon name="file-text" class="h-4 w-4" />
                                Open PDF
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
