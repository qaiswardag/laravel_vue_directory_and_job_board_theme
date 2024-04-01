<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBrandLogoRelation extends Model
{
    use HasFactory;

    protected $fillable = ["media_library_id", "store_id", "primary"];
}
