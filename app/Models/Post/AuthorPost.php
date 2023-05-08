<?php

namespace App\Models\Post;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorPost extends Model
{
    use HasFactory;

    // Define the table name for this model
    protected $table = "author_post";

    // Define the fillable attributes
    protected $fillable = ["user_id", "post_id"];
}
