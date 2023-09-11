<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategoryRelation extends Model
{
    use HasFactory;
    protected $fillable = ["post_id", "category_id"];
}
