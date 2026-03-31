<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LetterEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = Letter::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('topic', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.letters.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.letters.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = Letter::query()->create($this->payload($request));

        return redirect()
            ->route('admin.letters.edit', $record)
            ->with('status', 'Letter record created successfully.');
    }

    public function edit(Letter $letter): View
    {
        return view('admin.letters.form', [
            'record' => $letter,
            'values' => $this->values($letter),
        ]);
    }

    public function update(Request $request, Letter $letter): RedirectResponse
    {
        $letter->update($this->payload($request, $letter));

        return redirect()
            ->route('admin.letters.edit', $letter)
            ->with('status', 'Letter record updated successfully.');
    }

    public function destroy(Letter $letter): RedirectResponse
    {
        $letter->delete();

        return redirect()
            ->route('admin.letters.index')
            ->with('status', 'Letter record deleted successfully.');
    }

    private function values(?Letter $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'category' => $record?->category,
            'topic' => $record?->topic,
            'summary' => $record?->summary,
            'body' => $record?->body,
            'source_url' => $record?->source_url,
            'document_url' => $record?->document_url,
            'image' => $record?->image,
            'is_featured' => (bool) $record?->is_featured,
            'published_at' => $record?->published_at?->format('Y-m-d\TH:i'),
            'key_takeaways' => array_values($record?->key_takeaways ?? []),
            'attachments' => array_values($record?->attachments ?? []),
        ];
    }

    private function payload(Request $request, ?Letter $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('letters', 'slug')->ignore($record?->id)],
            'category' => ['required', 'string', 'max:255'],
            'topic' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'source_url' => ['nullable', 'string', 'max:2048'],
            'document_url' => ['nullable', 'string', 'max:2048'],
            'document_upload' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'key_takeaways' => ['nullable', 'array'],
            'key_takeaways.*' => ['nullable', 'string'],
            'attachment_labels' => ['nullable', 'array'],
            'attachment_labels.*' => ['nullable', 'string', 'max:255'],
            'attachment_urls' => ['nullable', 'array'],
            'attachment_urls.*' => ['nullable', 'string', 'max:2048'],
        ]);

        $takeaways = collect($validated['key_takeaways'] ?? [])
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values()
            ->all();

        $labels = $validated['attachment_labels'] ?? [];
        $urls = $validated['attachment_urls'] ?? [];

        $attachments = collect(range(0, max(count($labels), count($urls)) - 1))
            ->map(function (int $index) use ($labels, $urls): ?array {
                $label = trim((string) ($labels[$index] ?? ''));
                $url = trim((string) ($urls[$index] ?? ''));

                if ($label === '' && $url === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'url' => $url,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return [
            'title' => trim((string) $validated['title']),
            'slug' => $this->uniqueSlug(
                trim((string) ($validated['slug'] ?? '')),
                trim((string) $validated['title']),
                $record?->id
            ),
            'category' => trim((string) $validated['category']),
            'topic' => trim((string) ($validated['topic'] ?? '')) ?: null,
            'summary' => trim((string) $validated['summary']),
            'body' => trim((string) ($validated['body'] ?? '')) ?: null,
            'source_url' => trim((string) ($validated['source_url'] ?? '')) ?: null,
            'document_url' => $this->storeAsset(
                $request->file('document_upload'),
                'letters',
                'document',
                trim((string) ($validated['document_url'] ?? '')) ?: $record?->document_url
            ),
            'image' => $this->storeAsset(
                $request->file('image_upload'),
                'letters',
                'image',
                trim((string) ($validated['image'] ?? '')) ?: $record?->image
            ),
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $validated['published_at'] ?? null,
            'key_takeaways' => $takeaways,
            'attachments' => $attachments,
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'letter-record';
        $slug = $base;
        $counter = 2;

        while (
            Letter::query()
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function storeAsset(?UploadedFile $file, string $resource, string $field, ?string $current): ?string
    {
        if (! $file) {
            return $current;
        }

        $directory = public_path('uploads/admin/'.$resource.'/'.$field);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = now()->format('YmdHis').'-'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $file->move($directory, $filename.'.'.$extension);

        return '/uploads/admin/'.$resource.'/'.$field.'/'.$filename.'.'.$extension;
    }
}
