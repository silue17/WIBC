<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'position', 'bio', 'photo', 'social', 'sort_order'];

    protected $casts = ['social' => 'array'];
}
