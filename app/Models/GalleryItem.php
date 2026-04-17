<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $table    = 'gallery';
    protected $fillable = ['url', 'sort_order'];
}
