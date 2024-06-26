<?php

namespace App\Models;

use App\Models\Job\Job;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use App\Models\Store\Store;
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
        "address",
        "contact_page_url",
        "slug",
        "personal_team",
        "public",
        "logo_original",
        "logo_thumbnail",
        "logo_medium",
        "logo_large",

        "monday_opening_time",
        "monday_closing_time",

        "tuesday_opening_time",
        "tuesday_closing_time",

        "wednesday_opening_time",
        "wednesday_closing_time",

        "thursday_opening_time",
        "thursday_closing_time",

        "friday_opening_time",
        "friday_closing_time",

        "saturday_opening_time",
        "saturday_closing_time",

        "sunday_opening_time",
        "sunday_closing_time",

        "time_zone",
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

    public function posts()
    {
        return $this->hasMany(Post::class, "team_id", "id");
    }
    public function jobs()
    {
        return $this->hasMany(Job::class, "team_id", "id");
    }
    public function stores()
    {
        return $this->hasMany(Store::class, "team_id", "id");
    }
    public function media()
    {
        return $this->hasMany(MediaLibrary::class, "team_id", "id");
    }

    //
    //
    //
    //
    //
    //
    //
    public function owner()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function teamUsers()
    {
        return $this->belongsToMany(User::class)->withPivot("role");
    }

    public function coverImagesRelationship()
    {
        return $this->belongsToMany(
            MediaLibrary::class,
            "team_cover_image_relations",
            "team_id",
            "media_library_id"
        )->withPivot("primary");
    }

    public function logosRelationship()
    {
        return $this->belongsToMany(
            MediaLibrary::class,
            "team_logo_relations",
            "team_id",
            "media_library_id"
        )->withPivot("primary");
    }

    // Append the 'coverImage_i' and 'logos' attributes to the model
    protected $appends = ["coverImagesWithLogos"];

    // Define an accessor for 'coverImage_i' and 'logos'
    public function getCoverImagesWithLogosAttribute()
    {
        return [
            "cover_images" => $this->coverImagesRelationship,
            "logos" => $this->logosRelationship,
        ];
    }

    //
    //
    //
    //
    //
    //
    //
    //
    //
    protected static function boot()
    {
        parent::boot();

        // create a team
        static::creating(function ($team) {
            $number = 1; // set a default number
            $teamSlug = $team->slug;

            // generate a new slug using the first name and last name
            $slug = Str::lower(Str::slug($teamSlug, "_"));

            // check if the username already exists in the database
            while (static::where("slug", $slug)->exists()) {
                // If  slug exists, add the number suffix and check again
                $slug = Str::lower(Str::slug($teamSlug . "_" . $number, "_"));
                $number++; // increment the number
            }

            // assign the unique username to the user model
            $team->slug = $slug;
        });
    }
}
