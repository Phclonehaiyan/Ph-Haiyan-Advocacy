@extends('admin.layouts.app', ['pageTitle' => $definition['label']])

@section('content')
    @php
        $columns = $definition['index_columns'];
    @endphp

    <section class="admin-panel">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="admin-kicker">{{ $definition['singular'] }} archive</div>
                <h2 class="admin-heading">{{ $definition['label'] }}</h2>
                <p class="admin-copy">{{ $definition['description'] }}</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <form method="GET" class="flex items-center gap-3">
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Search {{ strtolower($definition['label']) }}"
                        class="w-full min-w-[18rem] rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100"
                    >
                    <button type="submit" class="btn-secondary !px-4 !py-2.5">Search</button>
                </form>

                <a href="{{ route('admin.resources.create', $resource) }}" class="btn-primary justify-center">
                    <x-icon name="plus" class="h-4 w-4" />
                    New {{ $definition['singular'] }}
                </a>
            </div>
        </div>

        <div class="admin-table-shell mt-8">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ \Illuminate\Support\Str::headline($column) }}</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                @foreach ($columns as $column)
                                    @php $value = data_get($record, $column); @endphp
                                    <td>
                                        @if (is_bool($value))
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $value ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $value ? 'Yes' : 'No' }}
                                            </span>
                                        @elseif ($value instanceof \Illuminate\Support\Carbon)
                                            {{ $value->format('M d, Y h:i A') }}
                                        @else
                                            {{ \Illuminate\Support\Str::limit(is_array($value) ? json_encode($value) : (string) $value, 120) ?: '—' }}
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('admin.resources.edit', [$resource, $record->getKey()]) }}" class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-pine-900 transition hover:border-pine-300 hover:bg-pine-50">
                                            <x-icon name="edit" class="h-3.5 w-3.5" />
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.resources.destroy', [$resource, $record->getKey()]) }}" method="POST" onsubmit="return confirm('Delete this {{ strtolower($definition['singular']) }}?');">
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
                                <td colspan="{{ count($columns) + 1 }}" class="px-4 py-10 text-center text-sm text-slate-500">No records found for this module.</td>
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
