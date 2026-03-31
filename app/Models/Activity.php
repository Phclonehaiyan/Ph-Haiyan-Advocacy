<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'content',
        'location',
        'image',
        'image_alt',
        'is_featured',
        'activity_date',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_featured' => 'boolean',
            'activity_date' => 'datetime',
        ];
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
