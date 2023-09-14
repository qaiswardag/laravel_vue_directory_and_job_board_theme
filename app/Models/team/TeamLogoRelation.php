<?php

namespace App\Models\team;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLogoRelation extends Model
{
    use HasFactory;
    protected $fillable = ["logo_id", "team_id"];
}
