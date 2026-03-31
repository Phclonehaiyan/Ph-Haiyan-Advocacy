<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
<meta name="robots" content="{{ $seo['robots'] }}">
<link rel="canonical" href="{{ $seo['canonical_url'] }}">

@if (filled($seo['keywords']))
    <meta name="keywords" content="{{ $seo['keywords'] }}">
@endif

<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical_url'] }}">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">

@if (filled($seo['image']))
    <meta property="og:image" content="{{ $seo['image'] }}">
@endif

<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">

@if (filled($seo['twitter_site']))
    <meta name="twitter:site" content="{{ $seo['twitter_site'] }}">
@endif

@if (filled($seo['image']))
    <meta name="twitter:image" content="{{ $seo['image'] }}">
@endif

@if (filled($seo['published_time']))
    <meta property="article:published_time" content="{{ $seo['published_time'] }}">
@endif

@if (filled($seo['modified_time']))
    <meta property="article:modified_time" content="{{ $seo['modified_time'] }}">
@endif

@if (filled(config('site.seo.google_site_verification')))
    <meta name="google-site-verification" content="{{ config('site.seo.google_site_verification') }}">
@endif

@foreach ($schemas as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endforeach

@if (filled(config('site.seo.ga_measurement_id')))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.seo.ga_measurement_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('site.seo.ga_measurement_id') }}');
    </script>
@endif
