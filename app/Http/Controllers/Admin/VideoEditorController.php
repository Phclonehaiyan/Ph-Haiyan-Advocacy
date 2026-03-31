<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VideoEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = Video::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('platform', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%')
                        ->orWhere('view_count_label', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.videos.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.videos.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = Video::query()->create($this->payload($request));

        return redirect()
            ->route('admin.videos.edit', $record)
            ->with('status', 'Video story created successfully.');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.form', [
            'record' => $video,
            'values' => $this->values($video),
        ]);
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $video->update($this->payload($request, $video));

        return redirect()
            ->route('admin.videos.edit', $video)
            ->with('status', 'Video story updated successfully.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()
            ->route('admin.videos.index')
            ->with('status', 'Video story deleted successfully.');
    }

    private function values(?Video $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'summary' => $record?->summary,
            'thumbnail' => $record?->thumbnail,
            'video_url' => $record?->video_url,
            'platform' => $record?->platform,
            'view_count_label' => $record?->view_count_label,
            'duration' => $record?->duration,
            'is_featured' => (bool) $record?->is_featured,
            'published_at' => $record?->published_at?->format('Y-m-d\TH:i'),
        ];
    }

    private function payload(Request $request, ?Video $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('videos', 'slug')->ignore($record?->id)],
            'summary' => ['required', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:2048'],
            'thumbnail_upload' => ['nullable', 'image', 'max:5120'],
            'video_url' => ['required', 'url', 'max:2048'],
            'platform' => ['nullable', 'string', 'max:100'],
            'view_count_label' => ['nullable', 'string', 'max:100'],
            'duration' => ['nullable', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => trim((string) $validated['title']),
            'slug' => $this->uniqueSlug(
                trim((string) ($validated['slug'] ?? '')),
                trim((string) $validated['title']),
                $record?->id
            ),
            'summary' => trim((string) $validated['summary']),
            'thumbnail' => $this->storeAsset(
                $request->file('thumbnail_upload'),
                'videos',
                'thumbnail',
                trim((string) ($validated['thumbnail'] ?? '')) ?: $record?->thumbnail
            ),
            'video_url' => trim((string) $validated['video_url']),
            'platform' => trim((string) ($validated['platform'] ?? '')) ?: null,
            'view_count_label' => trim((string) ($validated['view_count_label'] ?? '')) ?: null,
            'duration' => trim((string) ($validated['duration'] ?? '')) ?: null,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $validated['published_at'] ?? null,
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'video-story';
        $slug = $base;
        $counter = 2;

        while (
            Video::query()
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
