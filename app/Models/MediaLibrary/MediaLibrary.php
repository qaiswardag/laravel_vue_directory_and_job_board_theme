<?php

namespace App\Models\MediaLibrary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = "media_library";

    /**
     * All attributes are s assignable.
     *
     * @var string<int, string>
     */

    protected $fillable = [
        "user_id",
        "team_id",
        "name",
        "path",
        "size",
        "width",
        "height",
        "thumbnail_path",
        "medium_path",
        "large_path",
    ];
}
