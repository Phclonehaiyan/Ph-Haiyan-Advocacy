<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectEditorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $records = Project::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%')
                        ->orWhere('year', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', [
            'records' => $records,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'record' => null,
            'values' => $this->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = Project::query()->create($this->payload($request));

        return redirect()
            ->route('admin.projects.edit', $record)
            ->with('status', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', [
            'record' => $project,
            'values' => $this->values($project),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->payload($request, $project));

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project deleted successfully.');
    }

    private function values(?Project $record = null): array
    {
        return [
            'title' => $record?->title,
            'slug' => $record?->slug,
            'category' => $record?->category,
            'year' => $record?->year,
            'summary' => $record?->summary,
            'description' => $record?->description,
            'image' => $record?->image,
            'sort_order' => $record?->sort_order,
            'is_featured' => (bool) $record?->is_featured,
        ];
    }

    private function payload(Request $request, ?Project $record = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('projects', 'slug')->ignore($record?->id)],
            'category' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],
            'summary' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'title' => trim((string) $validated['title']),
            'slug' => $this->uniqueSlug(
                trim((string) ($validated['slug'] ?? '')),
                trim((string) $validated['title']),
                $record?->id
            ),
            'category' => trim((string) $validated['category']),
            'year' => trim((string) ($validated['year'] ?? '')) ?: null,
            'summary' => trim((string) $validated['summary']),
            'description' => trim((string) $validated['description']),
            'image' => $this->storeAsset(
                $request->file('image_upload'),
                'projects',
                'image',
                trim((string) ($validated['image'] ?? '')) ?: $record?->image
            ),
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    private function uniqueSlug(string $provided, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($provided !== '' ? $provided : $title) ?: 'project';
        $slug = $base;
        $counter = 2;

        while (
            Project::query()
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
