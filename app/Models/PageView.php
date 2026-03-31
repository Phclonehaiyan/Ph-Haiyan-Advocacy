<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visitor_key',
        'session_id',
        'path',
        'route_name',
        'page_label',
        'referrer_host',
        'device_type',
        'ip_hash',
        'user_agent',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }
}
