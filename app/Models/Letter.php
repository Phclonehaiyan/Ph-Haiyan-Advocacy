<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'topic',
        'summary',
        'body',
        'key_takeaways',
        'attachments',
        'source_url',
        'document_url',
        'image',
        'image_alt',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'key_takeaways' => 'array',
            'attachments' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $builder): void {
            $builder
                ->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
