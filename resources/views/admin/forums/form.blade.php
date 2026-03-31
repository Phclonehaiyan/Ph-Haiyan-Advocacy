@extends('admin.layouts.app', ['pageTitle' => ($record ? 'Edit Forum Topic' : 'New Forum Topic')])

@section('content')
    @php
        $fieldValue = fn (string $key) => old($key, $values[$key] ?? '');
    @endphp

    <form action="{{ $record ? route('admin.forums.update', $record) : route('admin.forums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($record)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Archive editor</div>
                    <h2 class="admin-heading mt-2">{{ $record ? 'Edit Forum Topic' : 'Create Forum Topic' }}</h2>
                    <p class="admin-copy mt-3">Use this editor for the public forums archive, full discussion story pages, and record metadata shown across the site.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.forums.index') }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to Forums
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save Forum Topic
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Topic details</div>
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
                    <label for="starter_name" class="admin-label">Starter name</label>
                    <input id="starter_name" name="starter_name" type="text" value="{{ $fieldValue('starter_name') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="summary" class="admin-label">Short summary</label>
                    <textarea id="summary" name="summary" rows="4" class="admin-input mt-2">{{ $fieldValue('summary') }}</textarea>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Discussion story</div>
            <div class="mt-6">
                <label for="body" class="admin-label">Full topic body</label>
                <textarea id="body" name="body" rows="18" class="admin-input mt-2">{{ $fieldValue('body') }}</textarea>
                <p class="mt-2 text-xs leading-6 text-slate-500">This appears on the public forum story page. Plain text and simple HTML both work.</p>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Meta and publishing</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="status" class="admin-label">Status</label>
                        <select id="status" name="status" class="admin-input mt-2">
                            @foreach (['Open', 'Archived', 'Closed'] as $statusOption)
                                <option value="{{ $statusOption }}" @selected($fieldValue('status') === $statusOption)>{{ $statusOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tags" class="admin-label">Tags</label>
                        <input id="tags" name="tags" type="text" value="{{ $fieldValue('tags') }}" class="admin-input mt-2" placeholder="flood-control, forum, planning">
                    </div>
                    <div>
                        <label for="replies_count" class="admin-label">Replies count</label>
                        <input id="replies_count" name="replies_count" type="number" min="0" value="{{ $fieldValue('replies_count') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="views_count" class="admin-label">Views count</label>
                        <input id="views_count" name="views_count" type="number" min="0" value="{{ $fieldValue('views_count') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="last_activity_at" class="admin-label">Last activity</label>
                        <input id="last_activity_at" name="last_activity_at" type="datetime-local" value="{{ $fieldValue('last_activity_at') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="image" class="admin-label">Image path</label>
                        <input id="image" name="image" type="text" value="{{ $fieldValue('image') }}" class="admin-input mt-2" placeholder="/images/...">
                    </div>
                    <div>
                        <label for="image_upload" class="admin-label">Upload new image</label>
                        <input id="image_upload" name="image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                    </div>
                    <div class="flex flex-wrap gap-6 pt-7">
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $values['is_featured'] ?? false)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                            <span>Featured topic</span>
                        </label>
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
                            <input type="hidden" name="is_pinned" value="0">
                            <input type="checkbox" name="is_pinned" value="1" @checked(old('is_pinned', $values['is_pinned'] ?? false)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                            <span>Pinned topic</span>
                        </label>
                    </div>
                </div>

                <div class="admin-panel-subtle p-4">
                    <div class="admin-kicker">Current preview image</div>
                    @if ($fieldValue('image'))
                        <img src="{{ asset(ltrim((string) $fieldValue('image'), '/')) }}" alt="Forum image preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                    @endif
                </div>
            </div>
        </section>
    </form>
@endsection
