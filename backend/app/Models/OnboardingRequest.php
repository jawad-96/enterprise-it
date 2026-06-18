<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingRequest extends Model
{
    protected $fillable = ['email', 'organisation', 'account_type', 'status'];
}
