@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'left',
])

@php
    $classes = $align === 'center' ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl';
@endphp

<div {{ $attributes->class($classes) }}>
    @if ($eyebrow)
        <div class="eyebrow">{{ $eyebrow }}</div>
    @endif

    <h2 class="section-title mt-4 text-balance">{{ $title }}</h2>

    @if ($description)
        <p class="section-copy mt-4">{{ $description }}</p>
    @endif
</div>
