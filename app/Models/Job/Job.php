<?php

namespace App\Models\Job;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the fillable attributes
    protected $fillable = [
        "user_id",
        "team_id",
        "title",
        "slug",
        "content",
        "published",

        "cover_image_original",
        "cover_image_thumbnail",
        "cover_image_medium",
        "cover_image_large",

        "tags",
        "show_author",
        "trash",
        "author_id",
    ];

    // Define the relationship with the Team model
    public function team()
    {
        return $this->belongsTo(Team::class, "team_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function authors()
    {
        return $this->belongsToMany(User::class, "author_job");
    }

    public function categories()
    {
        return $this->belongsToMany(
            JobCategory::class,
            "job_category_relations",
            "job_id",
            "category_id"
        );
    }
    public function types()
    {
        return $this->belongsToMany(
            JobType::class,
            "job_type_relations",
            "job_id",
            "type_id"
        );
    }
    public function states()
    {
        return $this->belongsToMany(
            JobState::class,
            "job_state_relations",
            "job_id",
            "state_id"
        );
    }

    public function countries()
    {
        return $this->belongsToMany(
            JobCountry::class,
            "job_country_relations",
            "job_id",
            "country_id"
        );
    }
}
