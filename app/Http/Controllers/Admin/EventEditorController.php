<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = Event::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('venue', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('start_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.events.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = Event::query()->create($this->payload($request));

        return redirect()
            ->route('admin.events.edit', $record)
            ->with('status', 'Event created successfully.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.form', [
            'record' => $event,
            'values' => $this->values($event),
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update($this->payload($request, $event));

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('status', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('status', 'Event deleted successfully.');
    }

    private function values(?Event $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'category' => $record?->category,
            'summary' => $record?->summary,
            'description' => $record?->description,
            'location' => $record?->location,
            'venue' => $record?->venue,
            'image' => $record?->image,
            'is_featured' => (bool) $record?->is_featured,
            'is_published' => (bool) ($record?->is_published ?? true),
            'start_at' => $record?->start_at?->format('Y-m-d\TH:i'),
            'end_at' => $record?->end_at?->format('Y-m-d\TH:i'),
        ];
    }

    private function payload(Request $request, ?Event $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($record?->id)],
            'category' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:5120'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ]);

        return [
            'title' => trim((string) $validated['title']),
            'slug' => $this->uniqueSlug(
                trim((string) ($validated['slug'] ?? '')),
                trim((string) $validated['title']),
                $record?->id
            ),
            'category' => trim((string) $validated['category']),
            'summary' => trim((string) $validated['summary']),
            'description' => trim((string) ($validated['description'] ?? '')) ?: null,
            'location' => trim((string) ($validated['location'] ?? '')) ?: null,
            'venue' => trim((string) ($validated['venue'] ?? '')) ?: null,
            'image' => $this->storeAsset(
                $request->file('image_upload'),
                'events',
                'image',
                trim((string) ($validated['image'] ?? '')) ?: $record?->image
            ),
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'event';
        $slug = $base;
        $counter = 2;

        while (
            Event::query()
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
