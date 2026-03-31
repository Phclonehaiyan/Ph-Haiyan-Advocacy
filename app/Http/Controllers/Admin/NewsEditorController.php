<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = NewsPost::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('excerpt', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.news.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = NewsPost::query()->create($this->payload($request));

        return redirect()
            ->route('admin.news.edit', $record)
            ->with('status', 'News story created successfully.');
    }

    public function edit(NewsPost $newsPost): View
    {
        return view('admin.news.form', [
            'record' => $newsPost,
            'values' => $this->values($newsPost),
        ]);
    }

    public function update(Request $request, NewsPost $newsPost): RedirectResponse
    {
        $newsPost->update($this->payload($request, $newsPost));

        return redirect()
            ->route('admin.news.edit', $newsPost)
            ->with('status', 'News story updated successfully.');
    }

    public function destroy(NewsPost $newsPost): RedirectResponse
    {
        $newsPost->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'News story deleted successfully.');
    }

    private function values(?NewsPost $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'category' => $record?->category,
            'excerpt' => $record?->excerpt,
            'content' => $record?->content,
            'image' => $record?->image,
            'image_alt' => $record?->image_alt,
            'meta_title' => $record?->meta_title,
            'meta_description' => $record?->meta_description,
            'og_image' => $record?->og_image,
            'reading_time' => $record?->reading_time,
            'is_featured' => (bool) $record?->is_featured,
            'is_published' => $record ? (bool) $record->is_published : true,
            'published_at' => $record?->published_at?->format('Y-m-d\TH:i'),
        ];
    }

    private function payload(Request $request, ?NewsPost $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('news_posts', 'slug')->ignore($record?->id)],
            'category' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string', 'max:2048'],
            'og_image_upload' => ['nullable', 'image', 'max:5120'],
            'image_upload' => ['nullable', 'image', 'max:5120'],
            'reading_time' => ['nullable', 'integer', 'min:1'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => trim((string) $validated['title']),
            'slug' => $this->uniqueSlug(
                trim((string) ($validated['slug'] ?? '')),
                trim((string) $validated['title']),
                $record?->id
            ),
            'category' => trim((string) $validated['category']),
            'excerpt' => trim((string) $validated['excerpt']),
            'content' => trim((string) $validated['content']),
            'image' => $this->storeAsset(
                $request->file('image_upload'),
                'news',
                'image',
                trim((string) ($validated['image'] ?? '')) ?: $record?->image
            ),
            'image_alt' => trim((string) ($validated['image_alt'] ?? '')) ?: null,
            'meta_title' => trim((string) ($validated['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($validated['meta_description'] ?? '')) ?: null,
            'og_image' => $this->storeAsset(
                $request->file('og_image_upload'),
                'news',
                'og-image',
                trim((string) ($validated['og_image'] ?? '')) ?: $record?->og_image
            ),
            'reading_time' => $validated['reading_time'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $validated['published_at'] ?? null,
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'news-story';
        $slug = $base;
        $counter = 2;

        while (
            NewsPost::query()
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
