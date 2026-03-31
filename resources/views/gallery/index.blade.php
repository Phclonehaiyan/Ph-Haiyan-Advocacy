@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $galleryItems = $items->map(fn ($item) => [
            'id' => $item->id,
            'slug' => $item->slug,
            'title' => $item->title,
            'category' => $item->category,
            'summary' => $item->summary,
            'image' => asset(ltrim($item->image, '/')),
            'image_alt' => $item->image_alt ?: $item->title,
            'taken_at' => optional($item->taken_at)->format('M d, Y'),
        ])->values();
    @endphp

    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16" x-data="galleryBrowser(@js($galleryItems))" @keydown.escape.window="close()">
        <x-section-header
            eyebrow="Gallery Browser"
            title="A visual archive of field work, public meetings, and campaign activity."
            :description="$page->content['intro'] ?? null"
        />

        <div class="mt-10 flex flex-col gap-4 rounded-[32px] border border-pine-100 bg-white/90 p-5 shadow-soft lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap gap-3">
                <template x-for="category in availableCategories" :key="category">
                    <button
                        type="button"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition"
                        :class="selectedCategory === category ? 'bg-pine-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-pine-50 hover:text-pine-800'"
                        @click="selectedCategory = category"
                        x-text="category"
                    ></button>
                </template>
            </div>

            <label class="relative w-full max-w-sm">
                <input x-model.debounce.200ms="query" type="text" placeholder="Search the gallery" class="h-12 w-full rounded-2xl border border-slate-200 bg-sand-50 px-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100">
            </label>
        </div>

        <div x-show="filteredItems.length === 0" class="mt-10 rounded-[28px] border border-dashed border-slate-300 bg-white/85 px-6 py-8 text-center text-sm leading-7 text-slate-500 shadow-soft">
            No gallery items match your current search or category.
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <template x-for="item in filteredItems" :key="item.slug">
                <button type="button" class="group block h-full w-full scroll-mt-28 text-left" :id="'gallery-' + item.slug" @click="open(item)">
                    <div class="flex h-full flex-col overflow-hidden rounded-[30px] border border-pine-100 bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-float">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            <img :src="item.image" :alt="item.image_alt" class="h-full w-full object-contain bg-slate-50 p-3 transition duration-500 group-hover:scale-[1.02]">
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-center justify-between gap-3">
                                <span class="chip" x-text="item.category"></span>
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400" x-text="item.taken_at"></span>
                            </div>

                            <h3 class="mt-4 min-h-[3.5rem] text-xl font-semibold leading-[1.3] text-pine-950" x-text="item.title"></h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600" x-text="item.summary"></p>

                            <div class="mt-auto pt-5">
                                <span class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-pine-50 px-4 py-2 text-sm font-semibold text-pine-900 transition group-hover:border-pine-300 group-hover:bg-pine-100">
                                    <span>Open image</span>
                                    <x-icon name="arrow-up-right" class="h-4 w-4" />
                                </span>
                            </div>
                        </div>
                    </div>
                </button>
            </template>
        </div>

        <div x-cloak x-show="activeItem" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-pine-950/80 px-4 py-10 backdrop-blur-sm">
            <div class="relative max-h-full w-full max-w-5xl overflow-hidden rounded-[36px] bg-white shadow-float" @click.outside="close()">
                <button type="button" class="absolute right-5 top-5 z-10 inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/90 text-pine-950 shadow-soft" @click="close()">
                    <x-icon name="close" class="h-5 w-5" />
                </button>

                <div class="grid max-h-[80vh] overflow-auto lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="flex items-center justify-center bg-slate-100 p-6">
                        <img :src="activeItem?.image" :alt="activeItem?.image_alt" class="max-h-[70vh] w-full object-contain">
                    </div>
                    <div class="p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="chip" x-text="activeItem?.category"></span>
                            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400" x-text="activeItem?.taken_at"></span>
                        </div>
                        <h3 class="mt-5 font-display text-3xl font-semibold text-pine-950" x-text="activeItem?.title"></h3>
                        <p class="mt-5 text-base leading-8 text-slate-600" x-text="activeItem?.summary"></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
