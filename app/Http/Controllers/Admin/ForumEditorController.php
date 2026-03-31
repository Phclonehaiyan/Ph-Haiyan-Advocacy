<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumTopic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ForumEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = ForumTopic::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%')
                        ->orWhere('starter_name', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_activity_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.forums.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.forums.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = ForumTopic::query()->create($this->payload($request));

        return redirect()
            ->route('admin.forums.edit', $record)
            ->with('status', 'Forum topic created successfully.');
    }

    public function edit(ForumTopic $forumTopic): View
    {
        return view('admin.forums.form', [
            'record' => $forumTopic,
            'values' => $this->values($forumTopic),
        ]);
    }

    public function update(Request $request, ForumTopic $forumTopic): RedirectResponse
    {
        $forumTopic->update($this->payload($request, $forumTopic));

        return redirect()
            ->route('admin.forums.edit', $forumTopic)
            ->with('status', 'Forum topic updated successfully.');
    }

    public function destroy(ForumTopic $forumTopic): RedirectResponse
    {
        $forumTopic->delete();

        return redirect()
            ->route('admin.forums.index')
            ->with('status', 'Forum topic deleted successfully.');
    }

    private function values(?ForumTopic $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'category' => $record?->category,
            'summary' => $record?->summary,
            'body' => $record?->body,
            'image' => $record?->image,
            'starter_name' => $record?->starter_name,
            'status' => $record?->status,
            'tags' => implode(', ', $record?->tags ?? []),
            'replies_count' => $record?->replies_count,
            'views_count' => $record?->views_count,
            'is_featured' => (bool) $record?->is_featured,
            'is_pinned' => (bool) $record?->is_pinned,
            'last_activity_at' => $record?->last_activity_at?->format('Y-m-d\TH:i'),
        ];
    }

    private function payload(Request $request, ?ForumTopic $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('forum_topics', 'slug')->ignore($record?->id)],
            'category' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:5120'],
            'starter_name' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'replies_count' => ['nullable', 'integer', 'min:0'],
            'views_count' => ['nullable', 'integer', 'min:0'],
            'last_activity_at' => ['nullable', 'date'],
        ]);

        $tags = collect(explode(',', (string) ($validated['tags'] ?? '')))
            ->map(fn ($tag) => trim($tag))
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
            'summary' => trim((string) $validated['summary']),
            'body' => trim((string) ($validated['body'] ?? '')) ?: null,
            'image' => $this->storeAsset(
                $request->file('image_upload'),
                'forums',
                'image',
                trim((string) ($validated['image'] ?? '')) ?: $record?->image
            ),
            'starter_name' => trim((string) ($validated['starter_name'] ?? '')) ?: null,
            'status' => trim((string) ($validated['status'] ?? '')) ?: 'Open',
            'tags' => $tags,
            'replies_count' => $validated['replies_count'] ?? 0,
            'views_count' => $validated['views_count'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'is_pinned' => $request->boolean('is_pinned'),
            'last_activity_at' => $validated['last_activity_at'] ?? null,
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'forum-topic';
        $slug = $base;
        $counter = 2;

        while (
            ForumTopic::query()
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
