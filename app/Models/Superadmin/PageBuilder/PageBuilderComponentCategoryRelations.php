<?php

namespace App\Models\Superadmin\PageBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageBuilderComponentCategoryRelations extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = ["component_id", "category_id"];
}
