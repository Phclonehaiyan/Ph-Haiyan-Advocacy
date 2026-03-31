<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Login | PH Haiyan</title>
        <meta name="theme-color" content="#0f3d2e">
        <link rel="icon" type="image/png" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell flex min-h-screen items-center justify-center px-6 py-12 antialiased">
        <div class="absolute inset-x-0 top-0 h-80 bg-[radial-gradient(circle_at_top,rgba(15,61,46,0.18),transparent_70%)]"></div>
        <div class="relative w-full max-w-md">
            <div class="admin-panel p-8">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/brand/ph-haiyan-logo.png') }}" alt="PH Haiyan" class="h-14 w-auto">
                    <div>
                        <div class="font-display text-2xl font-semibold text-pine-950">PH Haiyan Admin</div>
                        <div class="admin-kicker mt-1">Content dashboard access</div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.login.store') }}" method="POST" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="admin-label">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="admin-input mt-2">
                    </div>

                    <div>
                        <label for="password" class="admin-label">Password</label>
                        <input id="password" name="password" type="password" required class="admin-input mt-2">
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                        <span>Keep me signed in on this device</span>
                    </label>

                    <button type="submit" class="btn-primary w-full justify-center">
                        Sign in to dashboard
                    </button>
                </form>

                <p class="mt-6 text-sm leading-6 text-slate-500">
                    Use your admin account to manage pages, news, letters, projects, gallery items, and contact messages across the website.
                </p>
            </div>
        </div>
    </body>
</html>
