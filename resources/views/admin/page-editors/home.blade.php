@extends('admin.layouts.app', ['pageTitle' => 'Home Page Editor'])

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
                        Save Home Page
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
            <div class="admin-kicker">Hero banner</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <label for="hero_title_line_1" class="admin-label">Hero line 1</label>
                            <input id="hero_title_line_1" name="hero_title_line_1" type="text" value="{{ $fieldValue('hero_title_line_1') }}" class="admin-input mt-2">
                        </div>
                        <div>
                            <label for="hero_title_line_2" class="admin-label">Hero line 2</label>
                            <input id="hero_title_line_2" name="hero_title_line_2" type="text" value="{{ $fieldValue('hero_title_line_2') }}" class="admin-input mt-2">
                        </div>
                        <div>
                            <label for="hero_title_line_3" class="admin-label">Hero line 3</label>
                            <input id="hero_title_line_3" name="hero_title_line_3" type="text" value="{{ $fieldValue('hero_title_line_3') }}" class="admin-input mt-2">
                        </div>
                    </div>

                    <div>
                        <label for="hero_intro" class="admin-label">Hero introduction</label>
                        <textarea id="hero_intro" name="hero_intro" rows="4" class="admin-input mt-2">{{ $fieldValue('hero_intro') }}</textarea>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <label for="hero_chip_1" class="admin-label">Chip 1</label>
                            <input id="hero_chip_1" name="hero_chip_1" type="text" value="{{ $fieldValue('hero_chip_1') }}" class="admin-input mt-2">
                        </div>
                        <div>
                            <label for="hero_chip_2" class="admin-label">Chip 2</label>
                            <input id="hero_chip_2" name="hero_chip_2" type="text" value="{{ $fieldValue('hero_chip_2') }}" class="admin-input mt-2">
                        </div>
                        <div>
                            <label for="hero_chip_3" class="admin-label">Chip 3</label>
                            <input id="hero_chip_3" name="hero_chip_3" type="text" value="{{ $fieldValue('hero_chip_3') }}" class="admin-input mt-2">
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="hero_image" class="admin-label">Hero image path</label>
                            <input id="hero_image" name="hero_image" type="text" value="{{ $fieldValue('hero_image') }}" class="admin-input mt-2" placeholder="/images/...">
                        </div>
                        <div>
                            <label for="hero_image_upload" class="admin-label">Upload new hero image</label>
                            <input id="hero_image_upload" name="hero_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                        </div>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current hero image</div>
                    @if ($fieldValue('hero_image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('hero_image'), '/')) }}" alt="Homepage hero preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No hero image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Mission pillars</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-3">
                @foreach (range(1, 3) as $index)
                    <div class="admin-panel-subtle p-5">
                        <div class="admin-kicker">Pillar {{ $index }}</div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="pillar_{{ $index }}_icon" class="admin-label">Icon</label>
                                <select id="pillar_{{ $index }}_icon" name="pillar_{{ $index }}_icon" class="admin-select mt-2">
                                    @foreach ($iconOptions as $iconValue => $iconLabel)
                                        <option value="{{ $iconValue }}" @selected($fieldValue("pillar_{$index}_icon") === $iconValue)>{{ $iconLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="pillar_{{ $index }}_title" class="admin-label">Title</label>
                                <input id="pillar_{{ $index }}_title" name="pillar_{{ $index }}_title" type="text" value="{{ $fieldValue("pillar_{$index}_title") }}" class="admin-input mt-2">
                            </div>
                            <div>
                                <label for="pillar_{{ $index }}_description" class="admin-label">Description</label>
                                <textarea id="pillar_{{ $index }}_description" name="pillar_{{ $index }}_description" rows="5" class="admin-input mt-2">{{ $fieldValue("pillar_{$index}_description") }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">About preview section</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    <div>
                        <label for="about_heading" class="admin-label">Section heading</label>
                        <input id="about_heading" name="about_heading" type="text" value="{{ $fieldValue('about_heading') }}" class="admin-input mt-2">
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="about_image" class="admin-label">Image path</label>
                            <input id="about_image" name="about_image" type="text" value="{{ $fieldValue('about_image') }}" class="admin-input mt-2" placeholder="/images/...">
                        </div>
                        <div>
                            <label for="about_image_upload" class="admin-label">Upload new image</label>
                            <input id="about_image_upload" name="about_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                        </div>
                    </div>

                    @foreach (range(1, 3) as $index)
                        <div>
                            <label for="about_body_{{ $index }}" class="admin-label">Paragraph {{ $index }}</label>
                            <textarea id="about_body_{{ $index }}" name="about_body_{{ $index }}" rows="4" class="admin-input mt-2">{{ $fieldValue("about_body_{$index}") }}</textarea>
                        </div>
                    @endforeach
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current preview image</div>
                    @if ($fieldValue('about_image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('about_image'), '/')) }}" alt="About preview image" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No about preview image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Impact call to action</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    <div>
                        <label for="impact_title" class="admin-label">CTA heading</label>
                        <input id="impact_title" name="impact_title" type="text" value="{{ $fieldValue('impact_title') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="impact_description" class="admin-label">CTA description</label>
                        <textarea id="impact_description" name="impact_description" rows="4" class="admin-input mt-2">{{ $fieldValue('impact_description') }}</textarea>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="impact_background_image" class="admin-label">Background image path</label>
                            <input id="impact_background_image" name="impact_background_image" type="text" value="{{ $fieldValue('impact_background_image') }}" class="admin-input mt-2" placeholder="/images/...">
                        </div>
                        <div>
                            <label for="impact_background_image_upload" class="admin-label">Upload new background image</label>
                            <input id="impact_background_image_upload" name="impact_background_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                        </div>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current background image</div>
                    @if ($fieldValue('impact_background_image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('impact_background_image'), '/')) }}" alt="Impact section background preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No support background image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>
    </form>
@endsection
