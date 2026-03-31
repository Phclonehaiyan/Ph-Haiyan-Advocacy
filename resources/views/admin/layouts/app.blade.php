@php
    $resources = \App\Support\AdminResourceRegistry::all();
    $contentResources = collect($resources)->except(['pages', 'news', 'letters', 'projects', 'forums', 'events', 'gallery', 'videos'])->all();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow">
        <title>{{ ($pageTitle ?? 'Admin Dashboard') . ' | PH Haiyan Admin' }}</title>
        <meta name="theme-color" content="#0f3d2e">
        <link rel="icon" type="image/png" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell min-h-screen text-slate-900 antialiased">
        <div class="flex min-h-screen">
            <aside class="admin-sidebar hidden w-72 px-6 py-8 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/brand/ph-haiyan-logo.png') }}" alt="PH Haiyan" class="h-12 w-auto">
                    <div>
                        <div class="font-display text-xl font-semibold text-white">PH Haiyan Admin</div>
                        <div class="text-xs uppercase tracking-[0.26em] text-white/55">Content Dashboard</div>
                    </div>
                </a>

                <nav class="mt-10 space-y-8 text-sm">
                    <div class="space-y-2">
                        <div class="px-3 text-xs font-semibold uppercase tracking-[0.26em] text-white/45">Overview</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Dashboard</span>
                            <x-icon name="spark" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.analytics.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.analytics.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Analytics</span>
                            <x-icon name="chart" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.settings.edit') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.settings.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Site Settings</span>
                            <x-icon name="settings" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.messages.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Contact Messages</span>
                            <x-icon name="mail" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.password.edit') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.password.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Change Password</span>
                            <x-icon name="shield" class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="space-y-2">
                        <div class="px-3 text-xs font-semibold uppercase tracking-[0.26em] text-white/45">Page Editors</div>
                        @foreach ([
                            'home' => 'Home Page',
                            'about' => 'About Page',
                            'what-we-do' => 'What We Do Page',
                        ] as $pageEditorKey => $pageEditorLabel)
                            <a href="{{ route('admin.page-editors.edit', $pageEditorKey) }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.page-editors.*') && request()->route('pageKey') === $pageEditorKey ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                                <span>{{ $pageEditorLabel }}</span>
                                <x-icon name="edit" class="h-4 w-4" />
                            </a>
                        @endforeach
                    </div>

                    <div class="space-y-2">
                        <div class="px-3 text-xs font-semibold uppercase tracking-[0.26em] text-white/45">Story Editors</div>
                        <a href="{{ route('admin.news.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.news.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>News Archive</span>
                            <x-icon name="speaker" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.letters.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.letters.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Letters Archive</span>
                            <x-icon name="mail" class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="space-y-2">
                        <div class="px-3 text-xs font-semibold uppercase tracking-[0.26em] text-white/45">Archive Editors</div>
                        <a href="{{ route('admin.projects.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.projects.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Projects Archive</span>
                            <x-icon name="edit" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.forums.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.forums.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Forums Archive</span>
                            <x-icon name="edit" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.events.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Events Archive</span>
                            <x-icon name="calendar" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.gallery.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.gallery.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Gallery Archive</span>
                            <x-icon name="image" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.videos.index') }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.videos.*') ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                            <span>Video Stories</span>
                            <x-icon name="play" class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="space-y-2">
                        <div class="px-3 text-xs font-semibold uppercase tracking-[0.26em] text-white/45">Content</div>
                        @foreach ($contentResources as $key => $resource)
                            <a href="{{ route('admin.resources.index', $key) }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition {{ request()->routeIs('admin.resources.*') && request()->route('resource') === $key ? 'bg-white/12 text-white shadow-soft' : 'text-white/75 hover:bg-white/6 hover:text-white' }}">
                                <span>{{ $resource['label'] }}</span>
                                <span class="text-[11px] uppercase tracking-[0.22em] text-white/40">{{ $resource['singular'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </nav>
            </aside>

            <div class="flex-1">
                <header class="border-b border-pine-900/10 bg-white/80 backdrop-blur">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-8">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.26em] text-pine-700/70">Administration</div>
                            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-pine-950">{{ $pageTitle ?? 'Admin Dashboard' }}</h1>
                        </div>

                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.password.edit') }}" class="hidden items-center gap-2 rounded-full border border-pine-200 bg-white px-4 py-2 text-sm font-medium text-pine-900 transition hover:border-pine-300 hover:bg-pine-50 sm:inline-flex">
                                <x-icon name="shield" class="h-4 w-4" />
                                Change password
                            </a>

                            <div class="hidden text-right text-sm text-slate-500 sm:block">
                                <div class="font-medium text-pine-950">{{ auth()->user()->name }}</div>
                                <div>{{ auth()->user()->email }}</div>
                            </div>

                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-white px-4 py-2 text-sm font-medium text-pine-900 transition hover:border-pine-300 hover:bg-pine-50">
                                    <x-icon name="logout" class="h-4 w-4" />
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-soft">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-soft">
                            <div class="font-semibold">Please review the form fields below.</div>
                            <ul class="mt-2 space-y-1 text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
