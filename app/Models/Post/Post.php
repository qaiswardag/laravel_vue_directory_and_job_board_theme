<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Not all attributes are s assignable.
     *
     * @var string<int, string>
     */

    protected $fillable = [
        "user_id",
        "team_id",
        "title",
        "slug",
        "content",
        "published",
        "thumbnail",
        "tags",
    ];
}
