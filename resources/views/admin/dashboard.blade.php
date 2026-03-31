@extends('admin.layouts.app', ['pageTitle' => 'Dashboard'])

@section('content')
    @php
        $contentResources = collect($resources)->except(['pages', 'news', 'letters', 'projects', 'forums', 'events', 'gallery', 'videos'])->all();
        $pageEditors = [
            ['label' => 'Home Page', 'description' => 'Hero, mission pillars, about preview, and homepage CTA.', 'route' => route('admin.page-editors.edit', 'home')],
            ['label' => 'About Page', 'description' => 'Organization story, logo meaning, resilience narrative, and CTA.', 'route' => route('admin.page-editors.edit', 'about')],
            ['label' => 'What We Do Page', 'description' => 'Program overview, signature initiatives, and milestone story blocks.', 'route' => route('admin.page-editors.edit', 'what-we-do')],
        ];
        $storyEditors = [
            ['label' => 'News Archive', 'description' => 'Public updates and story pages with image, summary, and full article text.', 'route' => route('admin.news.index')],
            ['label' => 'Letters Archive', 'description' => 'Official correspondence, story-backed records, PDFs, and supporting files.', 'route' => route('admin.letters.index')],
        ];
        $archiveEditors = [
            ['label' => 'Projects Archive', 'description' => 'Project records used across the homepage, What We Do page, search, and the dedicated Projects page.', 'route' => route('admin.projects.index')],
            ['label' => 'Forums Archive', 'description' => 'Forum topics, public discussion records, and archive metadata for the forums section.', 'route' => route('admin.forums.index')],
            ['label' => 'Events Archive', 'description' => 'Forums, meetings, public activities, venues, and event scheduling details.', 'route' => route('admin.events.index')],
            ['label' => 'Gallery Archive', 'description' => 'Photo and campaign image records used across the public gallery experience.', 'route' => route('admin.gallery.index')],
            ['label' => 'Video Stories', 'description' => 'Featured reels and video cards with thumbnails, links, and publication metadata.', 'route' => route('admin.videos.index')],
        ];
    @endphp

    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="admin-panel">
                <div class="admin-kicker">{{ $stat['label'] }}</div>
                <div class="mt-4 text-4xl font-semibold tracking-tight text-pine-950">{{ $stat['value'] }}</div>
            </div>
        @endforeach
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <div class="admin-panel">
            <div class="admin-kicker">Page Editors</div>
            <h2 class="admin-heading mt-2">Edit the core website pages directly.</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($pageEditors as $editor)
                    <a href="{{ $editor['route'] }}" class="admin-panel-subtle transition hover:-translate-y-0.5 hover:border-pine-200 hover:bg-pine-50/70">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-lg font-semibold text-pine-950">{{ $editor['label'] }}</h3>
                            <x-icon name="edit" class="h-4 w-4 text-slate-400" />
                        </div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $editor['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-kicker">Story Editors</div>
            <h2 class="admin-heading mt-2">Manage news and public records without raw JSON.</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($storyEditors as $editor)
                    <a href="{{ $editor['route'] }}" class="admin-panel-subtle transition hover:-translate-y-0.5 hover:border-pine-200 hover:bg-pine-50/70">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-lg font-semibold text-pine-950">{{ $editor['label'] }}</h3>
                            <x-icon name="arrow-up-right" class="h-4 w-4 text-slate-400" />
                        </div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $editor['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mt-8">
        <div class="admin-panel">
            <div class="admin-kicker">Archive Editors</div>
            <h2 class="admin-heading mt-2">Manage projects and forums with dedicated editors.</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($archiveEditors as $editor)
                    <a href="{{ $editor['route'] }}" class="admin-panel-subtle transition hover:-translate-y-0.5 hover:border-pine-200 hover:bg-pine-50/70">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-lg font-semibold text-pine-950">{{ $editor['label'] }}</h3>
                            <x-icon name="arrow-up-right" class="h-4 w-4 text-slate-400" />
                        </div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $editor['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(0,0.7fr)]">
        <div class="admin-panel">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="admin-kicker">Content Modules</div>
                    <h2 class="admin-heading">Manage the public site data.</h2>
                </div>
                <a href="{{ route('admin.settings.edit') }}" class="btn-secondary !px-4 !py-2">
                    <x-icon name="settings" class="h-4 w-4" />
                    Site settings
                </a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($contentResources as $key => $resource)
                    <a href="{{ route('admin.resources.index', $key) }}" class="admin-panel-subtle transition hover:-translate-y-0.5 hover:border-pine-200 hover:bg-pine-50/70">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-lg font-semibold text-pine-950">{{ $resource['label'] }}</h3>
                            <x-icon name="arrow-up-right" class="h-4 w-4 text-slate-400" />
                        </div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $resource['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-panel">
                <div class="admin-kicker">Recent Messages</div>
                <div class="mt-5 space-y-4">
                    @forelse ($recentMessages as $message)
                        <a href="{{ route('admin.messages.show', $message) }}" class="block rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4 transition hover:border-pine-200 hover:bg-pine-50/60">
                            <div class="flex items-center justify-between gap-4">
                                <div class="font-medium text-pine-950">{{ $message->name }}</div>
                                <div class="text-xs uppercase tracking-[0.22em] text-slate-400">{{ $message->status }}</div>
                            </div>
                            <div class="mt-1 text-sm text-slate-600">{{ $message->subject }}</div>
                            <div class="mt-2 text-xs text-slate-400">{{ $message->submitted_at?->format('M d, Y h:i A') }}</div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No contact messages yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-kicker">Recent News</div>
                <div class="mt-5 space-y-4">
                    @foreach ($recentUpdates as $post)
                        <a href="{{ route('admin.news.edit', $post) }}" class="block rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4 transition hover:border-pine-200 hover:bg-pine-50/60">
                            <div class="font-medium text-pine-950">{{ $post->title }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $post->category }}</div>
                            <div class="mt-2 text-xs text-slate-400">{{ $post->published_at?->format('M d, Y') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-kicker">Archive Summary</div>
                <dl class="mt-5 grid grid-cols-2 gap-4 text-sm text-slate-600">
                    @foreach ($activitySummary as $label => $value)
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-4">
                            <dt class="text-xs uppercase tracking-[0.22em] text-slate-400">{{ ucfirst($label) }}</dt>
                            <dd class="mt-2 text-2xl font-semibold text-pine-950">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>
@endsection
