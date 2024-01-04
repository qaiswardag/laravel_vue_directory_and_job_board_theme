<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibraryUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "size",
        "path",
        "thumbnail_path",
        "medium_path",
        "large_path",
        "width",
        "height",
        "extension",
    ];
}
