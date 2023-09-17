<?php

namespace App\Models\Superadmin\PageBuilder;

use App\Models\MediaLibrary\MediaLibrary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageBuilderComponent extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the fillable attributes
    protected $fillable = ["title", "html_code", "published", "user_id"];

    public function categories()
    {
        return $this->belongsToMany(
            PageBuilderComponentCategory::class,
            "page_builder_component_category_relations",
            "component_id",
            "category_id"
        );
    }
    public function coverImages()
    {
        return $this->belongsToMany(
            MediaLibrary::class,
            "page_builder_cover_image_relations",
            "component_id",
            "media_library_id"
        )->withPivot("primary");
    }
}
