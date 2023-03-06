<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * All attributes are s assignable.
     *
     * @var string<int, string>
     */
    protected $guarded = [];
}
