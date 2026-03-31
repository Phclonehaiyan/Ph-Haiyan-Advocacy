@extends('admin.layouts.app', ['pageTitle' => ($record ? 'Edit Letter' : 'New Letter')])

@section('content')
    @php
        $fieldValue = fn (string $key) => old($key, $values[$key] ?? '');
        $takeawayValues = old('key_takeaways', $values['key_takeaways'] ?? []);
        $attachmentLabels = old('attachment_labels', collect($values['attachments'] ?? [])->pluck('label')->all());
        $attachmentUrls = old('attachment_urls', collect($values['attachments'] ?? [])->pluck('url')->all());
        $attachmentValues = collect(range(0, max(count($attachmentLabels), count($attachmentUrls)) - 1))
            ->map(fn (int $index) => [
                'label' => $attachmentLabels[$index] ?? '',
                'url' => $attachmentUrls[$index] ?? '',
            ])
            ->values()
            ->all();

        if ($takeawayValues === []) {
            $takeawayValues = [''];
        }

        if ($attachmentValues === []) {
            $attachmentValues = [['label' => '', 'url' => '']];
        }
    @endphp

    <form action="{{ $record ? route('admin.letters.update', $record) : route('admin.letters.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($record)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Public record editor</div>
                    <h2 class="admin-heading mt-2">{{ $record ? 'Edit Letter Record' : 'Create Letter Record' }}</h2>
                    <p class="admin-copy mt-3">Use this editor for official correspondence, story pages, PDFs, and related supporting files.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.letters.index') }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to Letters
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save Letter
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Record details</div>
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
                    <label for="topic" class="admin-label">Topic / issue area</label>
                    <input id="topic" name="topic" type="text" value="{{ $fieldValue('topic') }}" class="admin-input mt-2">
                </div>
                <div class="md:col-span-2">
                    <label for="summary" class="admin-label">Archive summary</label>
                    <textarea id="summary" name="summary" rows="4" class="admin-input mt-2">{{ $fieldValue('summary') }}</textarea>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Story page</div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="body" class="admin-label">Full story content</label>
                    <textarea id="body" name="body" rows="20" class="admin-input mt-2">{{ $fieldValue('body') }}</textarea>
                    <p class="mt-2 text-xs leading-6 text-slate-500">Use this for the public story page that opens when a visitor clicks “Read full story”.</p>
                </div>
            </div>
        </section>

        <section class="admin-panel" x-data='{
            takeaways: @json($takeawayValues),
            attachments: @json($attachmentValues)
        }'>
            <div class="admin-kicker">Key takeaways</div>
            <div class="mt-6 space-y-4">
                <template x-for="(takeaway, index) in takeaways" :key="index">
                    <div class="flex items-start gap-3">
                        <textarea :name="'key_takeaways[' + index + ']'" x-model="takeaways[index]" rows="3" class="admin-input"></textarea>
                        <button type="button" class="btn-secondary !px-3 !py-2" @click="takeaways.splice(index, 1)" x-show="takeaways.length > 1">
                            Remove
                        </button>
                    </div>
                </template>
                <button type="button" class="btn-secondary !px-4 !py-2.5" @click="takeaways.push('')">
                    <x-icon name="plus" class="h-4 w-4" />
                    Add takeaway
                </button>
            </div>

            <div class="admin-kicker mt-10">Supporting files</div>
            <div class="mt-6 space-y-4">
                <template x-for="(attachment, index) in attachments" :key="index">
                    <div class="admin-panel-subtle p-4">
                        <div class="grid gap-4 md:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)_auto] md:items-end">
                            <div>
                                <label class="admin-label">Attachment label</label>
                                <input :name="'attachment_labels[' + index + ']'" x-model="attachment.label" type="text" class="admin-input mt-2">
                            </div>
                            <div>
                                <label class="admin-label">Attachment URL or file path</label>
                                <input :name="'attachment_urls[' + index + ']'" x-model="attachment.url" type="text" class="admin-input mt-2" placeholder="/assets/... or /uploads/...">
                            </div>
                            <button type="button" class="btn-secondary !px-3 !py-2" @click="attachments.splice(index, 1)" x-show="attachments.length > 1">
                                Remove
                            </button>
                        </div>
                    </div>
                </template>
                <button type="button" class="btn-secondary !px-4 !py-2.5" @click="attachments.push({ label: '', url: '' })">
                    <x-icon name="plus" class="h-4 w-4" />
                    Add supporting file
                </button>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kicker">Main files and publishing</div>
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="source_url" class="admin-label">Source URL</label>
                        <input id="source_url" name="source_url" type="text" value="{{ $fieldValue('source_url') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="published_at" class="admin-label">Published at</label>
                        <input id="published_at" name="published_at" type="datetime-local" value="{{ $fieldValue('published_at') }}" class="admin-input mt-2">
                    </div>
                    <div>
                        <label for="document_url" class="admin-label">Main document path</label>
                        <input id="document_url" name="document_url" type="text" value="{{ $fieldValue('document_url') }}" class="admin-input mt-2" placeholder="/assets/... or /uploads/...">
                    </div>
                    <div>
                        <label for="document_upload" class="admin-label">Upload new main document</label>
                        <input id="document_upload" name="document_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                    </div>
                    <div>
                        <label for="image" class="admin-label">Preview image path</label>
                        <input id="image" name="image" type="text" value="{{ $fieldValue('image') }}" class="admin-input mt-2" placeholder="/images/...">
                    </div>
                    <div>
                        <label for="image_upload" class="admin-label">Upload new preview image</label>
                        <input id="image_upload" name="image_upload" type="file" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                    </div>
                    <div class="flex flex-wrap gap-6 md:col-span-2">
                        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $values['is_featured'] ?? false)) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                            <span>Featured record</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="admin-panel-subtle p-4">
                        <div class="admin-kicker">Current preview image</div>
                        @if ($fieldValue('image'))
                            <img src="{{ asset(ltrim((string) $fieldValue('image'), '/')) }}" alt="Letter image preview" class="mt-4 h-64 w-full rounded-2xl object-cover">
                        @else
                            <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-sm text-slate-500">No image selected yet.</div>
                        @endif
                    </div>
                    <div class="admin-panel-subtle p-4">
                        <div class="admin-kicker">Current main document</div>
                        @if ($fieldValue('document_url'))
                            <a href="{{ asset(ltrim((string) $fieldValue('document_url'), '/')) }}" target="_blank" class="mt-4 inline-flex text-sm font-medium text-pine-800 underline underline-offset-4">Open current document</a>
                        @else
                            <div class="mt-4 text-sm text-slate-500">No main document selected yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
