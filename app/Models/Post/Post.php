<?php

namespace App\Models\Post;

use App\Models\MediaLibrary\MediaLibrary;
use App\Models\post\PostCoverImageRelation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the fillable attributes
    protected $fillable = [
        "user_id",
        "team_id",
        "title",
        "slug",
        "content",
        "published",
        "tags",
        "show_author",
        "trash",
        "author_id",
    ];

    // Define the relationship with the Team model
    public function team()
    {
        return $this->belongsTo(Team::class, "team_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function authors()
    {
        return $this->belongsToMany(User::class, "author_post");
    }

    public function categories()
    {
        return $this->belongsToMany(
            PostCategory::class,
            "post_category_relations",
            "post_id",
            "category_id"
        );
    }
    public function coverImages()
    {
        return $this->belongsToMany(
            MediaLibrary::class,
            "post_cover_image_relations",
            "post_id",
            "media_library_id"
        );
    }
}
