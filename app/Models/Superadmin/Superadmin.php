<?php

namespace App\Models\Superadmin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superadmin extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = ["role", "user_id"];
}
