@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        use Illuminate\Support\Str;

        $resolveMedia = static fn (?string $path): ?string => blank($path)
            ? null
            : (Str::startsWith($path, ['http://', 'https://']) ? $path : asset(ltrim($path, '/')));

        $lettersData = $letters->map(fn ($letter) => [
            'slug' => $letter->slug,
            'title' => $letter->title,
            'category' => $letter->category,
            'topic' => $letter->topic,
            'summary' => $letter->summary,
            'image' => $resolveMedia($letter->image),
            'image_alt' => $letter->image_alt ?: $letter->title,
            'document_url' => $letter->document_url ?: '#',
            'show_url' => route('letters.show', $letter),
            'attachments_count' => count($letter->attachments ?? []),
            'record_files_count' => 1 + count($letter->attachments ?? []),
            'published_at' => optional($letter->published_at)->format('M d, Y'),
            'published_month' => optional($letter->published_at)->format('M'),
            'published_day' => optional($letter->published_at)->format('d'),
            'year' => optional($letter->published_at)->format('Y'),
        ])->values();
    @endphp

    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Featured Letter"
            title="Official correspondence paired with the public story behind each letter."
            :description="$page->content['intro'] ?? null"
        />

        @if ($featuredLetter)
            <div class="mt-12">
                <article class="overflow-hidden rounded-[36px] border border-white/80 bg-white/94 shadow-soft lg:grid lg:grid-cols-[0.88fr_1.12fr]">
                    <div class="flex min-h-80 items-center justify-center bg-slate-50/40">
                        <img src="{{ $resolveMedia($featuredLetter->image) }}" alt="{{ $featuredLetter->image_alt ?: $featuredLetter->title }}" class="h-full max-h-[30rem] w-full object-contain">
                    </div>
                    <div class="flex flex-col justify-between p-8 lg:p-10">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="chip">{{ $featuredLetter->category }}</span>
                                <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                                    <x-icon name="calendar" class="h-4 w-4" />
                                    {{ $featuredLetter->published_at?->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-pine-100 bg-pine-50 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-pine-700">
                                <span class="inline-block h-2 w-2 rounded-full bg-pine-600"></span>
                                Story-backed public record
                            </div>
                            <h2 class="mt-6 font-display text-4xl font-semibold leading-[1.02] text-pine-950 lg:text-[3.1rem]">{{ $featuredLetter->title }}</h2>
                            <div class="mt-4 text-sm font-semibold uppercase tracking-[0.22em] text-pine-700">{{ $featuredLetter->topic }}</div>
                            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-600">{{ $featuredLetter->summary }}</p>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('letters.show', $featuredLetter) }}" class="btn-primary gap-2">
                                <x-icon name="eye" class="h-4 w-4" />
                                Read letter story
                            </a>
                            <a href="{{ $featuredLetter->document_url ?: '#' }}" target="_blank" rel="noreferrer" class="btn-secondary gap-2">
                                <x-icon name="file-text" class="h-4 w-4" />
                                Open PDF
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @endif
    </section>

    <section class="section-shell py-12 lg:py-16" x-data="letterArchive(@js($lettersData))">
        <div class="flex flex-col gap-8">
            <x-section-header
                eyebrow="Archive"
                title="Search, filter, and open the full record behind each letter."
                description="This archive now preserves the official document and the narrative context carried by PH Haiyan's old letters page."
            />

            <div class="rounded-[32px] border border-white/80 bg-white/88 p-4 shadow-soft backdrop-blur lg:p-5">
                <div class="grid gap-3 lg:grid-cols-[minmax(0,1.2fr)_0.8fr_0.8fr]">
                    <label class="relative block">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Search letters</span>
                        <input x-model.debounce.200ms="query" type="text" placeholder="Search by title, issue, or topic" class="h-[3.25rem] w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Filter by category</span>
                        <select x-model="selectedCategory" class="h-[3.25rem] w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100">
                            <template x-for="category in availableCategories" :key="category">
                                <option :value="category" x-text="category"></option>
                            </template>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Filter by year</span>
                        <select x-model="selectedYear" class="h-[3.25rem] w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100">
                            <template x-for="year in availableYears" :key="year">
                                <option :value="year" x-text="year"></option>
                            </template>
                        </select>
                    </label>
                </div>
            </div>
        </div>

        <div x-show="filteredLetters.length === 0" class="mt-10 rounded-[28px] border border-dashed border-slate-300 bg-white/85 px-6 py-8 text-center text-sm leading-7 text-slate-500 shadow-soft">
            No letters match your current search or filter.
        </div>

        <div class="mt-10 space-y-12">
            <template x-for="[year, records] in groupedLetters" :key="year">
                <section class="space-y-5" x-data="{ expanded: false }">
                    <div class="flex items-center gap-4">
                        <h2 class="font-display text-3xl font-semibold text-pine-950" x-text="year"></h2>
                        <span class="rounded-full border border-pine-100 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-pine-700" x-text="records.length + ' record' + (records.length > 1 ? 's' : '')"></span>
                        <span class="h-px flex-1 bg-pine-100"></span>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 2xl:grid-cols-3">
                        <template x-for="(letter, index) in records" :key="letter.slug">
                            <article x-show="expanded || index < 3" x-transition.opacity.duration.200ms class="flex h-full flex-col overflow-hidden rounded-[28px] border border-white/80 bg-white/95 shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-float">
                                <div class="border-b border-slate-100 bg-slate-50/50 p-4 pb-0">
                                    <div class="aspect-[16/9] overflow-hidden rounded-t-[22px] rounded-b-[18px] bg-white">
                                        <template x-if="letter.image">
                                            <img :src="letter.image" :alt="letter.image_alt" class="h-full w-full bg-white object-contain">
                                        </template>

                                        <template x-if="!letter.image">
                                            <div class="flex h-full flex-col bg-[linear-gradient(180deg,rgba(244,248,246,0.9),rgba(255,255,255,1))] p-5">
                                                <div class="flex items-start justify-between">
                                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-pine-50 text-pine-700 ring-1 ring-pine-100">
                                                        <x-icon name="file-text" class="h-4 w-4" />
                                                    </span>
                                                    <span class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400" x-text="letter.published_month + ' ' + letter.published_day"></span>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-pine-700">Official record</div>
                                                    <div class="mt-2 line-clamp-4 font-display text-2xl leading-[1.05] text-pine-950" x-text="letter.title"></div>
                                                </div>
                                                <div class="mt-auto pt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                    Archived letter preview
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="flex flex-1 flex-col p-5 lg:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2.5">
                                                <span class="chip" x-text="letter.category"></span>
                                                <span class="inline-flex items-center rounded-full border border-pine-100 bg-pine-50 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-pine-700">
                                                    Story page available
                                                </span>
                                            </div>
                                            <div class="mt-4 text-[10px] font-semibold uppercase tracking-[0.24em] text-pine-700" x-text="letter.topic"></div>
                                        </div>

                                        <div class="shrink-0 text-right">
                                            <div class="text-[10px] font-semibold uppercase tracking-[0.22em] text-slate-400">Date</div>
                                            <div class="mt-2 text-sm font-semibold text-pine-950" x-text="letter.published_at"></div>
                                        </div>
                                    </div>

                                    <h3 class="mt-4 font-display text-[1.7rem] font-semibold leading-[1.08] text-pine-950">
                                        <a :href="letter.show_url" class="transition hover:text-pine-700" x-text="letter.title"></a>
                                    </h3>
                                    <p class="mt-4 line-clamp-4 text-[15px] leading-8 text-slate-600" x-text="letter.summary"></p>

                                    <div class="mt-5 flex flex-wrap items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5">
                                            <x-icon name="file-text" class="h-4 w-4" />
                                            <span x-text="letter.record_files_count + ' record file' + (letter.record_files_count > 1 ? 's' : '')"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5" x-show="letter.attachments_count > 0">
                                            <x-icon name="arrow-up-right" class="h-4 w-4" />
                                            <span x-text="letter.attachments_count + ' supporting file' + (letter.attachments_count > 1 ? 's' : '')"></span>
                                        </span>
                                    </div>

                                    <div class="mt-6 flex items-center justify-between gap-3 border-t border-slate-100 pt-5">
                                        <a :href="letter.show_url" class="inline-flex min-h-[2.8rem] items-center justify-center gap-2 rounded-full bg-pine-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-pine-800">
                                            <x-icon name="eye" class="h-4 w-4" />
                                            Read full story
                                        </a>
                                        <a :href="letter.document_url" target="_blank" rel="noreferrer" class="inline-flex min-h-[2.8rem] items-center justify-center gap-2 rounded-full border border-pine-200 bg-white px-4 py-3 text-sm font-semibold text-pine-900 transition hover:border-pine-300 hover:bg-pine-50">
                                            <x-icon name="file-text" class="h-4 w-4" />
                                            Open PDF
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </template>
                    </div>

                    <div x-show="records.length > 3" class="flex justify-center pt-2">
                        <button
                            type="button"
                            @click="expanded = !expanded"
                            class="inline-flex min-h-[3rem] items-center justify-center gap-2 rounded-full border border-pine-200 bg-white px-5 py-3 text-sm font-semibold text-pine-900 shadow-soft transition hover:border-pine-300 hover:bg-pine-50"
                        >
                            <span x-text="expanded ? 'Show fewer records' : 'See ' + (records.length - 3) + ' more letter' + ((records.length - 3) > 1 ? 's' : '')"></span>
                            <x-icon name="chevron-down" class="h-4 w-4 transition" x-bind:class="expanded ? 'rotate-180' : ''" />
                        </button>
                    </div>
                </section>
            </template>
        </div>
    </section>
@endsection
