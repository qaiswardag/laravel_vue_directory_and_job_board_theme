<?php

namespace App\Models\Superadmin\PageBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageBuilderCoverImageRelation extends Model
{
    use HasFactory;

    protected $fillable = ["component_id", "media_library_id", "primary"];
}
