<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'features', 'sort_order'];

    protected $casts = ['features' => 'array'];
}
