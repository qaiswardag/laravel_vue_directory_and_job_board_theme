<?php

namespace App\Models\team;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLogoRelation extends Model
{
    use HasFactory;
    protected $fillable = ["media_library_id", "team_id", "primary"];
}
