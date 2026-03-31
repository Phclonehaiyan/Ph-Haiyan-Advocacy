<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $seo = \App\Support\Seo\SeoManager::fromContext(get_defined_vars());
            $schemas = \App\Support\Seo\SchemaBuilder::forContext($seo, get_defined_vars());
        @endphp

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.seo.meta', ['seo' => $seo, 'schemas' => $schemas])
        <meta name="theme-color" content="#0f3d2e">
        <link rel="icon" type="image/png" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/brand/ph-haiyan-logo.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-sand-50 text-slate-900 antialiased">
        <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-[420px] bg-[radial-gradient(circle_at_top,_rgba(15,61,46,0.18),_transparent_58%),linear-gradient(180deg,_rgba(235,245,239,0.95),_rgba(248,246,242,0.98))]"></div>
        <div class="pointer-events-none fixed inset-x-0 top-24 -z-10 mx-auto hidden h-[560px] max-w-7xl rounded-full bg-[radial-gradient(circle,_rgba(18,116,133,0.09),_transparent_68%)] blur-3xl lg:block"></div>

        <x-topbar />
        <x-navbar />

        <main class="relative">
            @yield('content')
        </main>

        <x-footer />

        @stack('scripts')
    </body>
</html>
