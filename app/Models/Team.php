<?php

namespace App\Models;

use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Illuminate\Support\Str;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "personal_team" => "boolean",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string<int, string>
     */
    protected $fillable = [
        "user_id",
        "name",
        "personal_team",
        "public",
        "logo",
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        "created" => TeamCreated::class,
        "updated" => TeamUpdated::class,
        "deleted" => TeamDeleted::class,
    ];

    // protected $with = ["posts"];

    public function posts()
    {
        return $this->hasMany(Post::class, "team_id", "id");
    }
    public function media()
    {
        return $this->hasMany(MediaLibrary::class, "team_id", "id");
    }

    /**
     * Generate a unique reference ID for the model.
     *
     * The function is being called automatically by Laravel's
     * Eloquent ORM when a new Team model instance is being created.
     *
     *
     * The creating event is fired when a new instance of
     * the model is being created and saved to the database.
     * This event is fired just before the model is actually
     * saved, which means that you can use it to modify
     * any attributes on the model before it's saved.
     * In this case, the boot method in the model is used
     * to register the creating event listener, which generates
     * a unique reference ID for the new team instance.
     *
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            do {
                // generate a random string

                $randomSlugString = Str::lower(
                    Str::slug(Str::random(rand(8, 14)), "_")
                );

                // convert the random string to lowercase using strtolower
                $team->reference_id = strtolower($randomSlugString);
            } while (
                static::where("reference_id", $team->reference_id)->exists()
            );
        });
    }
}
