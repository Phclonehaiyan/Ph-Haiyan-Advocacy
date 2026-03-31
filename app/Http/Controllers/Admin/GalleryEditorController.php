<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = GalleryItem::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('taken_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.gallery.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = GalleryItem::query()->create($this->payload($request));

        return redirect()
            ->route('admin.gallery.edit', $record)
            ->with('status', 'Gallery item created successfully.');
    }

    public function edit(GalleryItem $galleryItem): View
    {
        return view('admin.gallery.form', [
            'record' => $galleryItem,
            'values' => $this->values($galleryItem),
        ]);
    }

    public function update(Request $request, GalleryItem $galleryItem): RedirectResponse
    {
        $galleryItem->update($this->payload($request, $galleryItem));

        return redirect()
            ->route('admin.gallery.edit', $galleryItem)
            ->with('status', 'Gallery item updated successfully.');
    }

    public function destroy(GalleryItem $galleryItem): RedirectResponse
    {
        $galleryItem->delete();

        return redirect()
            ->route('admin.gallery.index')
            ->with('status', 'Gallery item deleted successfully.');
    }

    private function values(?GalleryItem $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'category' => $record?->category,
            'summary' => $record?->summary,
            'image' => $record?->image,
            'is_featured' => (bool) $record?->is_featured,
            'sort_order' => $record?->sort_order,
            'taken_at' => $record?->taken_at?->format('Y-m-d\TH:i'),
        ];
    }

    private function payload(Request $request, ?GalleryItem $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('gallery_items', 'slug')->ignore($record?->id)],
            'category' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'taken_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => trim((string) $validated['title']),
            'slug' => $this->uniqueSlug(
                trim((string) ($validated['slug'] ?? '')),
                trim((string) $validated['title']),
                $record?->id
            ),
            'category' => trim((string) $validated['category']),
            'summary' => trim((string) ($validated['summary'] ?? '')) ?: null,
            'image' => $this->storeAsset(
                $request->file('image_upload'),
                'gallery',
                'image',
                trim((string) ($validated['image'] ?? '')) ?: $record?->image
            ),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'taken_at' => $validated['taken_at'] ?? null,
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'gallery-item';
        $slug = $base;
        $counter = 2;

        while (
            GalleryItem::query()
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
