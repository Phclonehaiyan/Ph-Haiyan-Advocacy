@extends('admin.layouts.app', ['pageTitle' => ($record ? 'Edit Video Story' : 'New Video Story')])

@section('content')
    @php
        $fieldValue = fn (string $key) => old($key, $values[$key] ?? '');
    @endphp

    <form action="{{ $record ? route('admin.videos.update', $record) : route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($record)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Archive editor</div>
                    <h2 class="admin-heading mt-2">{{ $record ? 'Edit Video Story' : 'Create Video Story' }}</h2>
                    <p class="admin-copy mt-3">Use this editor for homepage videos, platform links, thumbnails, and view-count labels.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.videos.index') }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to Videos
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save Video Story
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Video details</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div><label for="title" class="admin-label">Title</label><input id="title" name="title" type="text" value="{{ $fieldValue('title') }}" class="admin-input mt-2"></div>
                <div><label for="slug" class="admin-label">Slug</label><input id="slug" name="slug" type="text" value="{{ $fieldValue('slug') }}" class="admin-input mt-2" placeholder="Leave blank to generate automatically"></div>
                <div><label for="platform" class="admin-label">Platform</label><input id="platform" name="platform" type="text" value="{{ $fieldValue('platform') }}" class="admin-input mt-2" placeholder="Facebook Reel"></div>
                <div><label for="duration" class="admin-label">Duration label</label><input id="duration" name="duration" type="text" value="{{ $fieldValue('duration') }}" class="admin-input mt-2" placeholder="01:45"></div>
                <div><label for="view_count_label" class="admin-label">View count label</label><input id="view_count_label" name="view_count_label" type="text" value="{{ $fieldValue('view_count_label') }}" class="admin-input mt-2" placeholder="5K views"></div>
                <div><label for="published_at" class="admin-label">Published at</label><input id="published_at" name="published_at" type="datetime-local" value="{{ $fieldValue('published_at') }}" class="admin-input mt-2"></div>
                <div class="md:col-span-2"><label for="video_url" class="admin-label">Video URL</label><input id="video_url" name="video_url" type="url" value="{{ $fieldValue('video_url') }}" class="admin-input mt-2"></div>
                <div class="md:col-span-2"><label for="summary" class="admin-label">Short summary</label><textarea id="summary" name="summary" rows="4" class="admin-input mt-2">{{ $fieldValue('summary') }}</textarea></div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Thumbnail and feature</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="grid gap-6 md:grid-cols-2">
                    <div><label for="thumbnail" class="admin-label">Thumbnail path</label><input id="thumbnail" name="thumbnail" type="text" value="{{ $fieldValue('thumbnail') }}" class="admin-input mt-2" placeholder="/images/..."></div>
                    <div><label for="thumbnail_upload" class="admin-label">Upload new thumbnail</label><input id="thumbnail_upload" name="thumbnail_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900"></div>
                    <div class="flex items-center pt-8 md:col-span-2">
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $values['is_featured'] ?? false)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30"><span>Featured video</span></label>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current thumbnail</div>
                    @if ($fieldValue('thumbnail'))
                        <img src="{{ asset(ltrim((string) $fieldValue('thumbnail'), '/')) }}" alt="Video thumbnail preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No thumbnail selected yet.</div>
                    @endif
                </div>
            </div>
        </section>
    </form>
@endsection
