<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $fillable = ["user_id", "team_id", "role"];
    protected $table = "team_user";
}
