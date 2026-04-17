<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    protected $table    = 'news';
    protected $fillable = ['title', 'date', 'description', 'photo', 'sort_order'];
}
