<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'slug',
        'body',
        'image_url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
