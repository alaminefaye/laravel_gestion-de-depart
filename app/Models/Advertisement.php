<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'video_path',
        'is_active',
        'display_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
