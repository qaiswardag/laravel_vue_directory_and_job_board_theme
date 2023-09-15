<?php

namespace App\Models\post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCoverImageRelation extends Model
{
    use HasFactory;
    protected $fillable = ["media_library_id", "post_id", "primary"];
}
