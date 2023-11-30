<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostStoreRelation extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = ["store_id", "post_id"];
}
