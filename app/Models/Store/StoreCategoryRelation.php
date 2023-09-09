<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCategoryRelation extends Model
{
    use HasFactory;

    protected $fillable = ["store_id", "category_id"];
}
