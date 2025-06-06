<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'language_id',
        'name',
        'slug'
    ];
}
