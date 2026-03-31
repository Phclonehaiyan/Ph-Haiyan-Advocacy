<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'body',
        'image',
        'starter_name',
        'status',
        'tags',
        'replies_count',
        'views_count',
        'is_featured',
        'is_pinned',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'last_activity_at' => 'datetime',
        ];
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
