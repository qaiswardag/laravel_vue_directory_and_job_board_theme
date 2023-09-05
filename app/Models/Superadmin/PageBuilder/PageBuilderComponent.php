<?php

namespace App\Models\Superadmin\PageBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageBuilderComponent extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = [
        "title",
        "html_code",
        "cover_image_original",
        "cover_image_thumbnail",
        "cover_image_medium",
        "cover_image_large",
    ];

    public function categories()
    {
        return $this->belongsToMany(
            PageBuilderComponentCategory::class,
            "page_builder_component_category_relations",
            "component_id",
            "category_id"
        );
    }
}
