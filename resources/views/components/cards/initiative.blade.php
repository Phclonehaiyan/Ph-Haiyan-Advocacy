@props([
    'icon',
    'title',
    'description',
    'showIcon' => true,
])

<article class="surface-card group h-full">
    @if ($showIcon)
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 transition duration-300 group-hover:-translate-y-1 group-hover:bg-pine-900 group-hover:text-white">
            <x-icon :name="$icon" class="h-6 w-6" />
        </div>
    @endif
    <h3 class="{{ $showIcon ? 'mt-6' : '' }} text-xl font-semibold text-pine-950">{{ $title }}</h3>
    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $description }}</p>
</article>
