@extends('admin.layouts.app', ['pageTitle' => ($record ? 'Edit ' : 'New ') . $definition['singular']])

@section('content')
    <form action="{{ $record ? route('admin.resources.update', [$resource, $record->getKey()]) : route('admin.resources.store', $resource) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($record)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">{{ $definition['singular'] }} editor</div>
                    <h2 class="admin-heading mt-2">{{ $record ? 'Edit ' . $definition['singular'] : 'Create ' . $definition['singular'] }}</h2>
                    <p class="admin-copy mt-3">{{ $definition['description'] }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.resources.index', $resource) }}" class="btn-secondary !px-4 !py-2.5">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Back to {{ $definition['label'] }}
                    </a>
                    <button type="submit" class="btn-primary">
                        <x-icon name="save" class="h-4 w-4" />
                        Save {{ $definition['singular'] }}
                    </button>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($definition['fields'] as $name => $field)
                    <div class="{{ in_array($field['type'], ['textarea', 'json'], true) ? 'md:col-span-2' : '' }}">
                        <label for="{{ $name }}" class="admin-label">{{ $field['label'] }}</label>

                        @switch($field['type'])
                            @case('textarea')
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] ?? 5 }}" class="admin-input mt-2 min-h-[9rem]">{{ old($name, $values[$name]) }}</textarea>
                                @break

                            @case('json')
                                <textarea
                                    id="{{ $name }}"
                                    name="{{ $name }}"
                                    rows="12"
                                    class="admin-input mt-2 font-mono"
                                    placeholder='{{ isset($field['template']) ? json_encode($field['template'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '{}' }}'
                                >{{ old($name, $values[$name]) }}</textarea>
                                @break

                            @case('checkbox')
                                <label class="mt-3 inline-flex items-center gap-3 text-sm text-slate-700">
                                    <input type="hidden" name="{{ $name }}" value="0">
                                    <input id="{{ $name }}" type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $values[$name])) class="rounded border-slate-300 bg-white text-pine-600 focus:ring-pine-500/30">
                                    <span>{{ $field['label'] }}</span>
                                </label>
                                @break

                            @case('select')
                                <select id="{{ $name }}" name="{{ $name }}" class="admin-select mt-2">
                                    <option value="">Select an option</option>
                                    @foreach ($field['options'] as $optionValue => $optionLabel)
                                        <option value="{{ $optionValue }}" @selected(old($name, $values[$name]) == $optionValue)>{{ $optionLabel }}</option>
                                    @endforeach
                                </select>
                                @break

                            @case('datetime')
                                <input id="{{ $name }}" name="{{ $name }}" type="datetime-local" value="{{ old($name, $values[$name]) }}" class="admin-input mt-2">
                                @break

                            @case('number')
                                <input id="{{ $name }}" name="{{ $name }}" type="number" value="{{ old($name, $values[$name]) }}" class="admin-input mt-2">
                                @break

                            @case('image')
                            @case('file')
                                <input id="{{ $name }}" name="{{ $name }}" type="text" value="{{ old($name, $values[$name]) }}" class="admin-input mt-2" placeholder="/images/... or /uploads/admin/...">
                                <input name="{{ $name }}_upload" type="file" class="mt-3 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-pine-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-pine-900">
                                @if (! empty($values[$name]))
                                    <div class="admin-panel-subtle mt-3 p-3">
                                        @if ($field['type'] === 'image')
                                            <img src="{{ asset(ltrim($values[$name], '/')) }}" alt="{{ $field['label'] }}" class="max-h-44 rounded-2xl object-contain">
                                        @else
                                            <a href="{{ asset(ltrim($values[$name], '/')) }}" target="_blank" class="text-sm font-medium text-pine-800 underline underline-offset-4">Open current file</a>
                                        @endif
                                    </div>
                                @endif
                                @break

                            @default
                                <input id="{{ $name }}" name="{{ $name }}" type="text" value="{{ old($name, $values[$name]) }}" class="admin-input mt-2">
                        @endswitch

                        @if (! empty($field['help']))
                            <p class="mt-2 text-xs leading-6 text-slate-500">{{ $field['help'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    </form>
@endsection
