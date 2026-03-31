@extends('admin.layouts.app', ['pageTitle' => 'What We Do Page Editor'])

@section('content')
    @php
        $fieldValue = fn (string $key) => old($key, $values[$key] ?? '');
    @endphp

    <form action="{{ route('admin.page-editors.update', $pageKey) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Dedicated page editor</div>
                    <h2 class="admin-heading mt-2">{{ $definition['label'] }}</h2>
                    <p class="admin-copy mt-3">{{ $definition['description'] }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to dashboard
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save What We Do Page
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Page basics</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="title" class="admin-label">Page title</label>
                    <input id="title" name="title" type="text" value="{{ $fieldValue('title') }}" class="admin-input mt-2">
                </div>
                <div>
                    <label for="hero_eyebrow" class="admin-label">Hero eyebrow</label>
                    <input id="hero_eyebrow" name="hero_eyebrow" type="text" value="{{ $fieldValue('hero_eyebrow') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="subtitle" class="admin-label">Page subtitle</label>
                    <textarea id="subtitle" name="subtitle" rows="3" class="admin-input mt-2">{{ $fieldValue('subtitle') }}</textarea>
                </div>
                <div>
                    <label for="hero_title" class="admin-label">Hero title</label>
                    <input id="hero_title" name="hero_title" type="text" value="{{ $fieldValue('hero_title') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="hero_subtitle" class="admin-label">Hero subtitle</label>
                    <textarea id="hero_subtitle" name="hero_subtitle" rows="3" class="admin-input mt-2">{{ $fieldValue('hero_subtitle') }}</textarea>
                </div>
                <div>
                    <label for="hero_image" class="admin-label">Hero image path</label>
                    <input id="hero_image" name="hero_image" type="text" value="{{ $fieldValue('hero_image') }}" class="admin-input mt-2" placeholder="/images/...">
                </div>
                <div>
                    <label for="hero_image_upload" class="admin-label">Upload new hero image</label>
                    <input id="hero_image_upload" name="hero_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                </div>
                <div>
                    <label for="meta_title" class="admin-label">Meta title</label>
                    <input id="meta_title" name="meta_title" type="text" value="{{ $fieldValue('meta_title') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="meta_description" class="admin-label">Meta description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" class="admin-input mt-2">{{ $fieldValue('meta_description') }}</textarea>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Program overview</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    @foreach (range(1, 3) as $index)
                        <div>
                            <label for="intro_{{ $index }}" class="admin-label">Overview paragraph {{ $index }}</label>
                            <textarea id="intro_{{ $index }}" name="intro_{{ $index }}" rows="4" class="admin-input mt-2">{{ $fieldValue("intro_{$index}") }}</textarea>
                        </div>
                    @endforeach

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="overview_image" class="admin-label">Overview image path</label>
                            <input id="overview_image" name="overview_image" type="text" value="{{ $fieldValue('overview_image') }}" class="admin-input mt-2" placeholder="/images/...">
                        </div>
                        <div>
                            <label for="overview_image_upload" class="admin-label">Upload new overview image</label>
                            <input id="overview_image_upload" name="overview_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                        </div>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current overview image</div>
                    @if ($fieldValue('overview_image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('overview_image'), '/')) }}" alt="Overview image" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Signature initiative cards</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-2">
                @foreach (range(1, 6) as $index)
                    <div class="admin-panel-subtle p-5">
                        <div class="admin-kicker">Initiative {{ $index }}</div>
                        <div class="mt-4 grid gap-4 md:grid-cols-[180px_minmax(0,1fr)]">
                            <div>
                                <label for="initiative_{{ $index }}_icon" class="admin-label">Icon</label>
                                <select id="initiative_{{ $index }}_icon" name="initiative_{{ $index }}_icon" class="admin-select mt-2">
                                    @foreach ($iconOptions as $iconValue => $iconLabel)
                                        <option value="{{ $iconValue }}" @selected($fieldValue("initiative_{$index}_icon") === $iconValue)>{{ $iconLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="initiative_{{ $index }}_title" class="admin-label">Title</label>
                                <input id="initiative_{{ $index }}_title" name="initiative_{{ $index }}_title" type="text" value="{{ $fieldValue("initiative_{$index}_title") }}" class="admin-input mt-2">
                            </div>
                            <div class="md:col-span-2">
                                <label for="initiative_{{ $index }}_description" class="admin-label">Description</label>
                                <textarea id="initiative_{{ $index }}_description" name="initiative_{{ $index }}_description" rows="4" class="admin-input mt-2">{{ $fieldValue("initiative_{$index}_description") }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Milestone story blocks</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-3">
                @foreach (range(1, 3) as $index)
                    <div class="admin-panel-subtle p-5">
                        <div class="admin-kicker">Story block {{ $index }}</div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="story_title_{{ $index }}" class="admin-label">Title</label>
                                <input id="story_title_{{ $index }}" name="story_title_{{ $index }}" type="text" value="{{ $fieldValue("story_title_{$index}") }}" class="admin-input mt-2">
                            </div>
                            <div>
                                <label for="story_description_{{ $index }}" class="admin-label">Description</label>
                                <textarea id="story_description_{{ $index }}" name="story_description_{{ $index }}" rows="5" class="admin-input mt-2">{{ $fieldValue("story_description_{$index}") }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </form>
@endsection
