@extends('admin.layouts.app', ['pageTitle' => ($record ? 'Edit News Story' : 'New News Story')])

@section('content')
    @php
        $fieldValue = fn (string $key) => old($key, $values[$key] ?? '');
        $gallerySlots = range(0, 5);
    @endphp

    <form action="{{ $record ? route('admin.news.update', $record) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($record)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Story editor</div>
                    <h2 class="admin-heading mt-2">{{ $record ? 'Edit News Story' : 'Create News Story' }}</h2>
                    <p class="admin-copy mt-3">Use this editor for archive stories, homepage updates, and full news pages.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.news.index') }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to News
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save News Story
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Basic details</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="title" class="admin-label">Title</label>
                    <input id="title" name="title" type="text" value="{{ $fieldValue('title') }}" class="admin-input mt-2">
                </div>
                <div>
                    <label for="slug" class="admin-label">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ $fieldValue('slug') }}" class="admin-input mt-2" placeholder="Leave blank to generate automatically">
                </div>
                <div>
                    <label for="category" class="admin-label">Category</label>
                    <input id="category" name="category" type="text" value="{{ $fieldValue('category') }}" class="admin-input mt-2">
                </div>
                <div>
                    <label for="reading_time" class="admin-label">Reading time (minutes)</label>
                    <input id="reading_time" name="reading_time" type="number" min="1" value="{{ $fieldValue('reading_time') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="excerpt" class="admin-label">Short summary</label>
                    <textarea id="excerpt" name="excerpt" rows="4" class="admin-input mt-2">{{ $fieldValue('excerpt') }}</textarea>
                </div>
                <div>
                    <label for="meta_title" class="admin-label">Meta title</label>
                    <input id="meta_title" name="meta_title" type="text" value="{{ $fieldValue('meta_title') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="meta_description" class="admin-label">Meta description</label>
                    <textarea id="meta_description" name="meta_description" rows="4" class="admin-input mt-2">{{ $fieldValue('meta_description') }}</textarea>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Story body</div>
            <div class="mt-6">
                <label for="content" class="admin-label">Full story content</label>
                <textarea id="content" name="content" rows="20" class="admin-input mt-2">{{ $fieldValue('content') }}</textarea>
                <p class="mt-2 text-xs leading-6 text-slate-500">Plain text and simple HTML both work here. This appears on the public news story page.</p>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Image and publishing</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="image" class="admin-label">Image path</label>
                        <input id="image" name="image" type="text" value="{{ $fieldValue('image') }}" class="admin-input mt-2" placeholder="/images/...">
                    </div>
                    <div>
                        <label for="image_upload" class="admin-label">Upload new image</label>
                        <input id="image_upload" name="image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                    </div>
                    <div>
                        <label for="image_alt" class="admin-label">Image alt text</label>
                        <input id="image_alt" name="image_alt" type="text" value="{{ $fieldValue('image_alt') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="og_image" class="admin-label">Social share image path</label>
                        <input id="og_image" name="og_image" type="text" value="{{ $fieldValue('og_image') }}" class="admin-input mt-2" placeholder="/images/...">
                    </div>
                    <div>
                        <label for="og_image_upload" class="admin-label">Upload social share image</label>
                        <input id="og_image_upload" name="og_image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                    </div>
                    <div>
                        <label for="published_at" class="admin-label">Published at</label>
                        <input id="published_at" name="published_at" type="datetime-local" value="{{ $fieldValue('published_at') }}" class="admin-input mt-2">
                    </div>
                    <div class="flex flex-wrap gap-6 pt-7">
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $values['is_featured'] ?? false)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                            <span>Featured story</span>
                        </label>
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $values['is_published'] ?? true)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                            <span>Published</span>
                        </label>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current preview image</div>
                    @if ($fieldValue('image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('image'), '/')) }}" alt="News image preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Article gallery</div>
            <h3 class="admin-heading mt-2">Add up to 6 supporting images for the full story page.</h3>
            <p class="admin-copy mt-3">Keep the preview image above for cards and social sharing. Use these slots for extra images inside the news article itself.</p>

            <div class="mt-6 grid gap-6 2xl:grid-cols-2">
                @foreach ($gallerySlots as $slot)
                    @php
                        $slotNumber = $slot + 1;
                        $slotImage = old("gallery_images.$slot.image", data_get($values, "gallery_images.$slot.image"));
                        $slotAlt = old("gallery_images.$slot.image_alt", data_get($values, "gallery_images.$slot.image_alt"));
                    @endphp

                    <div class="admin-panel-subtle flex h-full flex-col">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="admin-kicker">Supporting image {{ $slotNumber }}</div>
                                <div class="mt-2 text-sm text-slate-500">Optional story image, alt text, and caption.</div>
                            </div>
                            <div class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Slot {{ $slotNumber }}</div>
                        </div>

                        <div class="mt-5 flex flex-1 flex-col gap-5">
                            <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white/90">
                                @if ($slotImage)
                                    <img src="{{ asset(ltrim((string) $slotImage, '/')) }}" alt="Supporting image {{ $slotNumber }} preview" class="h-52 w-full object-cover">
                                @else
                                    <div class="flex h-52 items-center justify-center px-4 text-center text-sm text-slate-500">No image saved in this slot yet.</div>
                                @endif
                            </div>

                            <div>
                                <label for="gallery_images_{{ $slot }}_image" class="admin-label">Image path</label>
                                <input id="gallery_images_{{ $slot }}_image" name="gallery_images[{{ $slot }}][image]" type="text" value="{{ $slotImage }}" class="admin-input mt-2" placeholder="/uploads/...">
                            </div>

                            <div>
                                <label for="gallery_uploads_{{ $slot }}" class="admin-label">Upload image</label>
                                <input id="gallery_uploads_{{ $slot }}" name="gallery_uploads[{{ $slot }}]" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                            </div>

                            <div>
                                <label for="gallery_images_{{ $slot }}_image_alt" class="admin-label">Alt text</label>
                                <input id="gallery_images_{{ $slot }}_image_alt" name="gallery_images[{{ $slot }}][image_alt]" type="text" value="{{ $slotAlt }}" class="admin-input mt-2" placeholder="Describe the image naturally">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </form>
@endsection
