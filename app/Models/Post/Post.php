<?php

namespace App\Models\Post;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * All attributes are s assignable.
     *
     * @var string<int, string>
     */
    protected $guarded = [];

    // protected $with = ["belongsToTeam"];

    // public function belongsToTeam()
    // {
    //     return $this->hasOne(Team::class, "id", "team_id");
    // }
    //
    //
    //
    // protected $with = ["posts"];

    // public function posts()
    // {
    //     return $this->belongsTo(Team::class, "team_id", "id");
    // }
    //
    //
    //
    //
    // protected $with = ["user"];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, "user_id", "id");
    // }
}
