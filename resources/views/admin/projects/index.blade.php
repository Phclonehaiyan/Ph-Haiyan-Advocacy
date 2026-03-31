@extends('admin.layouts.app', ['pageTitle' => 'Projects Editor'])

@section('content')
    <section class="admin-panel">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="admin-kicker">Archive editor</div>
                <h2 class="admin-heading">Projects Archive</h2>
                <p class="admin-copy">Manage the project records used on the homepage, What We Do page, Projects archive, and search results without opening raw page JSON.</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <form method="GET" class="flex items-center gap-3">
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Search projects"
                        class="w-full min-w-[18rem] rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100"
                    >
                    <button type="submit" class="btn-secondary !px-4 !py-2.5">Search</button>
                </form>

                <a href="{{ route('admin.projects.create') }}" class="btn-primary justify-center">
                    <x-icon name="plus" class="h-4 w-4" />
                    New Project
                </a>
            </div>
        </div>

        <div class="admin-table-shell mt-8">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Category</th>
                            <th>Year</th>
                            <th>Order</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>
                                    <div class="min-w-[18rem]">
                                        <div class="font-medium text-pine-950">{{ $record->title }}</div>
                                        <div class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $record->slug }}</div>
                                        <div class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($record->summary, 120) }}</div>
                                    </div>
                                </td>
                                <td>{{ $record->category }}</td>
                                <td>{{ $record->year ?: '—' }}</td>
                                <td>{{ $record->sort_order }}</td>
                                <td>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $record->is_featured ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $record->is_featured ? 'Featured' : 'Standard' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('admin.projects.edit', $record) }}" class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-pine-900 transition hover:border-pine-300 hover:bg-pine-50">
                                            <x-icon name="edit" class="h-3.5 w-3.5" />
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.projects.destroy', $record) }}" method="POST" onsubmit="return confirm('Delete this project record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-100">
                                                <x-icon name="trash" class="h-3.5 w-3.5" />
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $records->links() }}
        </div>
    </section>
@endsection
