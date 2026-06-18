<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organisation',
        'email',
        'phone',
        'address',
        'service_category',
        'service_offering',
        'details',
    ];
}
