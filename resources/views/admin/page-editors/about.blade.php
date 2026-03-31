@extends('admin.layouts.app', ['pageTitle' => 'About Page Editor'])

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
                        Save About Page
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
            <div class="admin-kicker">Who we are</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    @foreach (range(1, 3) as $index)
                        <div>
                            <label for="who_we_are_{{ $index }}" class="admin-label">Paragraph {{ $index }}</label>
                            <textarea id="who_we_are_{{ $index }}" name="who_we_are_{{ $index }}" rows="4" class="admin-input mt-2">{{ $fieldValue("who_we_are_{$index}") }}</textarea>
                        </div>
                    @endforeach

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="who_we_are_image" class="admin-label">Image path</label>
                            <input id="who_we_are_image" name="who_we_are_image" type="text" value="{{ $fieldValue('who_we_are_image') }}" class="admin-input mt-2" placeholder="/images/...">
                        </div>
                        <div>
                            <label for="who_we_are_image_upload" class="admin-label">Upload new image</label>
                            <input id="who_we_are_image_upload" name="who_we_are_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                        </div>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current image</div>
                    @if ($fieldValue('who_we_are_image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('who_we_are_image'), '/')) }}" alt="Who we are image" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Logo meaning</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                @foreach (range(1, 4) as $index)
                    <div class="{{ $index === 4 ? 'md:col-span-2' : '' }}">
                        <label for="logo_paragraph_{{ $index }}" class="admin-label">Paragraph {{ $index }}</label>
                        <textarea id="logo_paragraph_{{ $index }}" name="logo_paragraph_{{ $index }}" rows="4" class="admin-input mt-2">{{ $fieldValue("logo_paragraph_{$index}") }}</textarea>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">CEO section</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="ceo_name" class="admin-label">Name</label>
                            <input id="ceo_name" name="ceo_name" type="text" value="{{ $fieldValue('ceo_name') }}" class="admin-input mt-2">
                        </div>
                        <div>
                            <label for="ceo_role" class="admin-label">Role</label>
                            <input id="ceo_role" name="ceo_role" type="text" value="{{ $fieldValue('ceo_role') }}" class="admin-input mt-2">
                        </div>
                    </div>

                    <div>
                        <label for="ceo_description" class="admin-label">Intro description</label>
                        <textarea id="ceo_description" name="ceo_description" rows="3" class="admin-input mt-2">{{ $fieldValue('ceo_description') }}</textarea>
                    </div>

                    @foreach (range(1, 3) as $index)
                        <div>
                            <label for="ceo_highlight_{{ $index }}" class="admin-label">Highlight {{ $index }}</label>
                            <textarea id="ceo_highlight_{{ $index }}" name="ceo_highlight_{{ $index }}" rows="4" class="admin-input mt-2">{{ $fieldValue("ceo_highlight_{$index}") }}</textarea>
                        </div>
                    @endforeach

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="ceo_image" class="admin-label">Image path</label>
                            <input id="ceo_image" name="ceo_image" type="text" value="{{ $fieldValue('ceo_image') }}" class="admin-input mt-2" placeholder="/images/...">
                        </div>
                        <div>
                            <label for="ceo_image_upload" class="admin-label">Upload new image</label>
                            <input id="ceo_image_upload" name="ceo_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                        </div>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current image</div>
                    @if ($fieldValue('ceo_image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('ceo_image'), '/')) }}" alt="CEO image" class="mt-4 h-72 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Mission and vision section</div>
            <div class="mt-6 space-y-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="mission_vision_eyebrow" class="admin-label">Section eyebrow</label>
                        <input id="mission_vision_eyebrow" name="mission_vision_eyebrow" type="text" value="{{ $fieldValue('mission_vision_eyebrow') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="mission_vision_badge" class="admin-label">Right-side badge</label>
                        <input id="mission_vision_badge" name="mission_vision_badge" type="text" value="{{ $fieldValue('mission_vision_badge') }}" class="admin-input mt-2">
                    </div>
                </div>

                <div>
                    <label for="mission_vision_heading" class="admin-label">Section heading</label>
                    <input id="mission_vision_heading" name="mission_vision_heading" type="text" value="{{ $fieldValue('mission_vision_heading') }}" class="admin-input mt-2">
                </div>

                <div>
                    <label for="mission_vision_description" class="admin-label">Section description</label>
                    <textarea id="mission_vision_description" name="mission_vision_description" rows="3" class="admin-input mt-2">{{ $fieldValue('mission_vision_description') }}</textarea>
                </div>

                <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="space-y-6">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="mission_label" class="admin-label">Mission label</label>
                                <input id="mission_label" name="mission_label" type="text" value="{{ $fieldValue('mission_label') }}" class="admin-input mt-2">
                            </div>
                            <div>
                                <label for="vision_label" class="admin-label">Vision label</label>
                                <input id="vision_label" name="vision_label" type="text" value="{{ $fieldValue('vision_label') }}" class="admin-input mt-2">
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="mission_title" class="admin-label">Mission card title</label>
                                <input id="mission_title" name="mission_title" type="text" value="{{ $fieldValue('mission_title') }}" class="admin-input mt-2">
                            </div>
                            <div>
                                <label for="vision_title" class="admin-label">Vision card title</label>
                                <input id="vision_title" name="vision_title" type="text" value="{{ $fieldValue('vision_title') }}" class="admin-input mt-2">
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="mission_quote" class="admin-label">Mission statement</label>
                                <textarea id="mission_quote" name="mission_quote" rows="5" class="admin-input mt-2">{{ $fieldValue('mission_quote') }}</textarea>
                            </div>
                            <div>
                                <label for="vision_quote" class="admin-label">Vision statement</label>
                                <textarea id="vision_quote" name="vision_quote" rows="5" class="admin-input mt-2">{{ $fieldValue('vision_quote') }}</textarea>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="mission_vision_background_image" class="admin-label">Background image path</label>
                                <input id="mission_vision_background_image" name="mission_vision_background_image" type="text" value="{{ $fieldValue('mission_vision_background_image') }}" class="admin-input mt-2" placeholder="/images/...">
                            </div>
                            <div>
                                <label for="mission_vision_background_image_upload" class="admin-label">Upload new background</label>
                                <input id="mission_vision_background_image_upload" name="mission_vision_background_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                            </div>
                        </div>
                    </div>

                    <div class="admin-panel-subtle p-4">
                        <div class="admin-kicker">Current background</div>
                        @if ($fieldValue('mission_vision_background_image'))
                            <img src="{{ asset(ltrim((string) $fieldValue('mission_vision_background_image'), '/')) }}" alt="Mission and vision background" class="mt-4 h-72 w-full rounded-2xl object-cover">
                        @else
                            <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Our story</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                @foreach (range(1, 5) as $index)
                    <div class="{{ $index === 5 ? 'md:col-span-2' : '' }}">
                        <label for="story_{{ $index }}" class="admin-label">Story paragraph {{ $index }}</label>
                        <textarea id="story_{{ $index }}" name="story_{{ $index }}" rows="5" class="admin-input mt-2">{{ $fieldValue("story_{$index}") }}</textarea>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Why climate resilience matters</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="why_heading" class="admin-label">Section heading</label>
                    <input id="why_heading" name="why_heading" type="text" value="{{ $fieldValue('why_heading') }}" class="admin-input mt-2">
                </div>
                @foreach (range(1, 4) as $index)
                    <div class="{{ $index === 4 ? 'md:col-span-2' : '' }}">
                        <label for="why_paragraph_{{ $index }}" class="admin-label">Paragraph {{ $index }}</label>
                        <textarea id="why_paragraph_{{ $index }}" name="why_paragraph_{{ $index }}" rows="4" class="admin-input mt-2">{{ $fieldValue("why_paragraph_{$index}") }}</textarea>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Closing call to action</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="cta_heading" class="admin-label">CTA heading</label>
                    <input id="cta_heading" name="cta_heading" type="text" value="{{ $fieldValue('cta_heading') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="cta_description" class="admin-label">CTA description</label>
                    <textarea id="cta_description" name="cta_description" rows="4" class="admin-input mt-2">{{ $fieldValue('cta_description') }}</textarea>
                </div>
            </div>
        </section>
    </form>
@endsection
