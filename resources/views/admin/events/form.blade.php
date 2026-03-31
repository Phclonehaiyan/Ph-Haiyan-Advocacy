@extends('admin.layouts.app', ['pageTitle' => ($record ? 'Edit Event' : 'New Event')])

@section('content')
    @php
        $fieldValue = fn (string $key) => old($key, $values[$key] ?? '');
    @endphp

    <form action="{{ $record ? route('admin.events.update', $record) : route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($record)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Archive editor</div>
                    <h2 class="admin-heading mt-2">{{ $record ? 'Edit Event' : 'Create Event' }}</h2>
                    <p class="admin-copy mt-3">Use this editor for public events, forum archives, schedules, and featured event highlights.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.events.index') }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to Events
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save Event
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Event details</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div><label for="title" class="admin-label">Title</label><input id="title" name="title" type="text" value="{{ $fieldValue('title') }}" class="admin-input mt-2"></div>
                <div><label for="slug" class="admin-label">Slug</label><input id="slug" name="slug" type="text" value="{{ $fieldValue('slug') }}" class="admin-input mt-2" placeholder="Leave blank to generate automatically"></div>
                <div><label for="category" class="admin-label">Category</label><input id="category" name="category" type="text" value="{{ $fieldValue('category') }}" class="admin-input mt-2"></div>
                <div><label for="location" class="admin-label">Location</label><input id="location" name="location" type="text" value="{{ $fieldValue('location') }}" class="admin-input mt-2"></div>
                <div class="md:col-span-2"><label for="venue" class="admin-label">Venue</label><input id="venue" name="venue" type="text" value="{{ $fieldValue('venue') }}" class="admin-input mt-2"></div>
                <div class="md:col-span-2"><label for="summary" class="admin-label">Short summary</label><textarea id="summary" name="summary" rows="4" class="admin-input mt-2">{{ $fieldValue('summary') }}</textarea></div>
                <div class="md:col-span-2"><label for="description" class="admin-label">Full description</label><textarea id="description" name="description" rows="16" class="admin-input mt-2">{{ $fieldValue('description') }}</textarea></div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Schedule and media</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="grid gap-6 md:grid-cols-2">
                    <div><label for="start_at" class="admin-label">Start at</label><input id="start_at" name="start_at" type="datetime-local" value="{{ $fieldValue('start_at') }}" class="admin-input mt-2"></div>
                    <div><label for="end_at" class="admin-label">End at</label><input id="end_at" name="end_at" type="datetime-local" value="{{ $fieldValue('end_at') }}" class="admin-input mt-2"></div>
                    <div><label for="image" class="admin-label">Image path</label><input id="image" name="image" type="text" value="{{ $fieldValue('image') }}" class="admin-input mt-2" placeholder="/images/..."></div>
                    <div><label for="image_upload" class="admin-label">Upload new image</label><input id="image_upload" name="image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900"></div>
                    <div class="flex flex-wrap gap-6 pt-7 md:col-span-2">
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $values['is_featured'] ?? false)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30"><span>Featured event</span></label>
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700"><input type="hidden" name="is_published" value="0"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $values['is_published'] ?? true)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30"><span>Published</span></label>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current preview image</div>
                    @if ($fieldValue('image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('image'), '/')) }}" alt="Event image preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>
    </form>
@endsection
