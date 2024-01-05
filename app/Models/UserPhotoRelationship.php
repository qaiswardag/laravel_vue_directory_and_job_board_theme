<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhotoRelationship extends Model
{
    use HasFactory;
    protected $fillable = ["media_library_id", "user_id", "primary"];
}
