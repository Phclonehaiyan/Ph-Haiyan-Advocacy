<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResourceRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JsonException;

class ResourceController extends Controller
{
    public function index(Request $request, string $resource): View
    {
        $definition = AdminResourceRegistry::get($resource);
        $modelClass = $definition['model'];
        $query = $modelClass::query();

        $search = trim((string) $request->query('q', ''));

        if ($search !== '' && ! empty($definition['searchable'])) {
            $query->where(function (Builder $builder) use ($definition, $search): void {
                foreach ($definition['searchable'] as $index => $column) {
                    if ($index === 0) {
                        $builder->where($column, 'like', '%'.$search.'%');
                    } else {
                        $builder->orWhere($column, 'like', '%'.$search.'%');
                    }
                }
            });
        }

        foreach ($definition['default_sort'] ?? ['id' => 'desc'] as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return view('admin.resources.index', [
            'resource' => $resource,
            'definition' => $definition,
            'records' => $query->paginate(15)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(string $resource): View
    {
        $definition = AdminResourceRegistry::get($resource);

        return view('admin.resources.form', [
            'resource' => $resource,
            'definition' => $definition,
            'record' => null,
            'values' => $this->formValues($definition),
        ]);
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $definition = AdminResourceRegistry::get($resource);
        $modelClass = $definition['model'];
        $payload = $this->payloadFromRequest($request, $resource, $definition);
        $record = $modelClass::query()->create($payload);

        return redirect()
            ->route('admin.resources.edit', [$resource, $record->getKey()])
            ->with('status', $definition['singular'].' created successfully.');
    }

    public function edit(string $resource, string $record): View
    {
        $definition = AdminResourceRegistry::get($resource);
        $model = $this->findRecord($definition, $record);

        return view('admin.resources.form', [
            'resource' => $resource,
            'definition' => $definition,
            'record' => $model,
            'values' => $this->formValues($definition, $model),
        ]);
    }

    public function update(Request $request, string $resource, string $record): RedirectResponse
    {
        $definition = AdminResourceRegistry::get($resource);
        $model = $this->findRecord($definition, $record);
        $payload = $this->payloadFromRequest($request, $resource, $definition, $model);
        $model->update($payload);

        return redirect()
            ->route('admin.resources.edit', [$resource, $model->getKey()])
            ->with('status', $definition['singular'].' updated successfully.');
    }

    public function destroy(string $resource, string $record): RedirectResponse
    {
        $definition = AdminResourceRegistry::get($resource);
        $model = $this->findRecord($definition, $record);
        $model->delete();

        return redirect()
            ->route('admin.resources.index', $resource)
            ->with('status', $definition['singular'].' deleted successfully.');
    }

    private function findRecord(array $definition, string $record): Model
    {
        return $definition['model']::query()->findOrFail($record);
    }

    private function payloadFromRequest(Request $request, string $resource, array $definition, ?Model $record = null): array
    {
        $rules = [];
        $attributes = [];

        foreach ($definition['fields'] as $name => $field) {
            $fieldRules = $field['rules'];
            $rules[$name] = is_callable($fieldRules) ? $fieldRules($record?->getKey()) : $fieldRules;
            $attributes[$name] = Str::headline($name);

            if (in_array($field['type'], ['image', 'file'], true)) {
                $rules[$name.'_upload'] = $field['upload_rules'] ?? ['nullable', 'file'];
            }
        }

        $validated = $request->validate($rules, [], $attributes);
        $payload = [];

        foreach ($definition['fields'] as $name => $field) {
            $type = $field['type'];

            if ($type === 'checkbox') {
                $payload[$name] = $request->boolean($name);

                continue;
            }

            if ($type === 'json') {
                $raw = trim((string) ($validated[$name] ?? ''));
                if ($raw === '') {
                    $payload[$name] = [];
                } else {
                    try {
                        $payload[$name] = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
                    } catch (JsonException) {
                        throw ValidationException::withMessages([
                            $name => 'Please enter valid JSON for '.$attributes[$name].'.',
                        ]);
                    }
                }

                continue;
            }

            if (in_array($type, ['image', 'file'], true)) {
                if ($request->hasFile($name.'_upload')) {
                    $payload[$name] = $this->storeUpload($request->file($name.'_upload'), $resource, $name);
                } else {
                    $payload[$name] = trim((string) ($validated[$name] ?? '')) ?: null;
                }

                continue;
            }

            $value = $validated[$name] ?? null;

            if (is_string($value)) {
                $value = trim($value);
            }

            $payload[$name] = $value === '' ? null : $value;
        }

        return $payload;
    }

    private function storeUpload(UploadedFile $file, string $resource, string $field): string
    {
        $directory = public_path('uploads/admin/'.$resource.'/'.$field);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = now()->format('YmdHis').'-'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $file->move($directory, $filename.'.'.$extension);

        return '/uploads/admin/'.$resource.'/'.$field.'/'.$filename.'.'.$extension;
    }

    private function formValues(array $definition, ?Model $record = null): array
    {
        $values = [];

        foreach ($definition['fields'] as $name => $field) {
            $value = $record?->getAttribute($name);

            if ($field['type'] === 'json') {
                if (is_array($value)) {
                    $values[$name] = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                } elseif (isset($field['template'])) {
                    $values[$name] = json_encode($field['template'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                } else {
                    $values[$name] = '[]';
                }
            } elseif ($field['type'] === 'checkbox') {
                $values[$name] = (bool) $value;
            } elseif ($field['type'] === 'datetime') {
                $values[$name] = $value ? $record->{$name}?->format('Y-m-d\TH:i') : null;
            } else {
                $values[$name] = $value;
            }
        }

        return $values;
    }
}
