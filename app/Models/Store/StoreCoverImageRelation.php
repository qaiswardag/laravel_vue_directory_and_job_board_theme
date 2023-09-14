<?php

namespace App\Models\store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCoverImageRelation extends Model
{
    use HasFactory;
    protected $fillable = ["media_library_id", "store_id"];
}
