@props([
    'icon',
    'title',
    'description',
])

<article class="surface-card group h-full">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-pine-50 text-pine-700 transition duration-300 group-hover:-translate-y-1 group-hover:bg-pine-900 group-hover:text-white">
        <x-icon :name="$icon" class="h-6 w-6" />
    </div>
    <h3 class="mt-6 font-display text-2xl font-semibold text-pine-950">{{ $title }}</h3>
    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $description }}</p>
</article>
