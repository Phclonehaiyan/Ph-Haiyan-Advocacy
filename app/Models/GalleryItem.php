<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'image',
        'image_alt',
        'is_featured',
        'sort_order',
        'taken_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'taken_at' => 'datetime',
        ];
    }
}
