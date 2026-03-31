<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsPostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_post_id',
        'image',
        'image_alt',
        'caption',
        'sort_order',
    ];

    public function newsPost(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class);
    }
}
