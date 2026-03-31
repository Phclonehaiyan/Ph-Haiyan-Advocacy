@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $content = $page->content ?? [];
        $projectDirectory = $projects->map(fn (\App\Models\Project $project) => [
            'slug' => $project->slug,
            'title' => $project->title,
            'category' => $project->category,
            'year' => $project->year,
            'summary' => $project->summary,
            'description' => $project->description,
            'image' => asset(ltrim((string) $project->image, '/')),
            'image_alt' => $project->image_alt ?: $project->title,
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
        <div class="grid gap-10 lg:grid-cols-[1.04fr_0.96fr] lg:items-start">
            <div class="space-y-5 text-base leading-8 text-slate-600">
                <x-section-header
                    eyebrow="Projects Overview"
                    title="A cleaner archive of PH Haiyan's project portfolio."
                />

                @foreach ($content['intro'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="surface-card h-fit overflow-hidden p-0">
                <img
                    src="/images/imported/projects/legacy/4c.jpg"
                    alt="PH Haiyan climate resilience project archive"
                    class="h-full min-h-[320px] w-full object-cover"
                >
            </div>
        </div>
    </section>

    <section id="project-directory" x-data="projectCatalog(@js($projectDirectory))" class="section-shell py-12 lg:py-16">
        <div>
            <x-section-header
                eyebrow="Project Directory"
                title="Browse the original project and initiative records."
                description="Search by title or filter by theme, then open each project card to read the full legacy description from the original site."
            />
        </div>

        <div class="mt-8 rounded-[30px] border border-white/80 bg-white/92 p-4 shadow-soft sm:p-5 lg:p-6">
            <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-start">
                <label class="block">
                    <span class="sr-only">Search projects</span>
                    <input
                        x-model="query"
                        type="text"
                        placeholder="Search projects by name or theme..."
                        class="form-input rounded-[22px] border-pine-100 bg-white px-5 py-4 text-base shadow-none"
                    >
                </label>

                <div class="space-y-3 xl:max-w-[820px]">
                    <div class="flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                        <x-icon name="leaf" class="h-4 w-4 text-pine-700" />
                        <span>Filter by theme</span>
                        <span class="h-px w-10 bg-pine-100"></span>
                    </div>

                    <div class="flex flex-wrap gap-2.5 pt-1">
                        <template x-for="category in availableCategories" :key="category">
                            <button
                                type="button"
                                @click="selectedCategory = category"
                                :class="selectedCategory === category
                                    ? 'border-pine-950 bg-pine-950 text-white shadow-soft'
                                    : 'border-pine-100 bg-white text-slate-600 hover:border-pine-200 hover:bg-pine-50 hover:text-pine-900'"
                                class="inline-flex min-h-11 items-center rounded-full border px-4 py-2.5 text-sm font-semibold transition whitespace-nowrap"
                                x-text="category"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="filteredProjects.length === 0" class="mt-10 rounded-[28px] border border-dashed border-slate-300 bg-white/85 px-6 py-8 text-center text-sm leading-7 text-slate-500 shadow-soft">
            No projects match your current search or filter.
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <template x-for="project in filteredProjects" :key="project.slug">
                <article :id="'project-' + project.slug" class="group scroll-mt-28 overflow-hidden rounded-[32px] border border-white/80 bg-white/94 shadow-soft transition hover:-translate-y-1 hover:shadow-float">
                    <button type="button" class="block h-full w-full text-left" @click="open(project)">
                        <div class="overflow-hidden">
                            <img :src="project.image" :alt="project.image_alt ?? project.title" loading="lazy" decoding="async" class="h-56 w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                        </div>

                        <div class="p-6">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="chip" x-text="project.category"></span>
                                <template x-if="project.year">
                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500" x-text="project.year"></span>
                                </template>
                            </div>

                            <h3 class="mt-4 text-[1.7rem] font-semibold leading-[1.18] text-pine-950" x-text="project.title"></h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600" x-text="project.summary"></p>

                            <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-pine-200 bg-pine-50 px-4 py-2 text-sm font-semibold text-pine-900 transition group-hover:border-pine-300 group-hover:bg-pine-100">
                                <span>View details</span>
                                <x-icon name="arrow-up-right" class="h-4 w-4" />
                            </div>
                        </div>
                    </button>
                </article>
            </template>
        </div>

        <div
            x-cloak
            x-show="activeProject"
            x-transition.opacity
            @keydown.escape.window="close()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-pine-950/70 px-4 py-8 backdrop-blur-sm"
        >
            <div @click="close()" class="absolute inset-0"></div>

            <div class="relative max-h-[90vh] w-full max-w-5xl overflow-y-auto rounded-[34px] border border-white/20 bg-white shadow-[0_28px_90px_rgba(7,17,13,0.24)]">
                <button
                    type="button"
                    @click="close()"
                    class="absolute right-4 top-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-soft transition hover:border-slate-300 hover:text-slate-900"
                >
                    <x-icon name="close" class="h-5 w-5" />
                </button>

                <div class="grid gap-0 lg:grid-cols-[0.96fr_1.04fr]">
                    <div class="overflow-hidden">
                        <img :src="activeProject?.image" :alt="activeProject?.image_alt ?? activeProject?.title" class="h-full min-h-72 w-full object-cover lg:min-h-[560px]">
                    </div>

                    <div class="p-6 sm:p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="chip" x-text="activeProject?.category"></span>
                            <template x-if="activeProject?.year">
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500" x-text="activeProject?.year"></span>
                            </template>
                        </div>

                        <h2 class="mt-5 font-display text-4xl font-semibold leading-[1.02] text-pine-950" x-text="activeProject?.title"></h2>
                        <p class="mt-5 text-base leading-8 text-slate-600" x-text="activeProject?.summary"></p>

                        <div class="mt-8 rounded-[28px] border border-slate-200/80 bg-[linear-gradient(135deg,_rgba(248,250,252,0.95),_rgba(255,255,255,0.92))] px-6 py-6">
                            <div class="text-xs font-semibold uppercase tracking-[0.24em] text-pine-700">Legacy project record</div>
                            <p class="mt-4 text-sm leading-8 text-slate-600" x-text="activeProject?.description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
