<?php

namespace App\Models\Store;

use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the fillable attributes
    protected $fillable = [
        "user_id",
        "team_id",
        "title",
        "slug",
        "address",
        "floor",
        "content",
        "published",
        "featured",
        "tags",
        "show_author",
        "trash",
        "contact_page_url",
        "brand_website_url",

        "time_zone", // Example "Asia/Dubai",
        "use_team_opening_hours",
        "use_store_opening_hours",

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
        return $this->belongsToMany(User::class, "author_store")->select(
            "users.first_name",
            "users.last_name",
            "users.profile_photo_path",
            "users.id"
        );
    }

    public function states()
    {
        return $this->belongsToMany(
            StoreState::class,
            "store_state_relations",
            "store_id",
            "state_id"
        );
    }

    public function categories()
    {
        return $this->belongsToMany(
            StoreCategory::class,
            "store_category_relations",
            "store_id",
            "category_id"
        );
    }
    public function coverImages()
    {
        return $this->belongsToMany(
            MediaLibrary::class,
            "store_cover_image_relations",
            "store_id",
            "media_library_id"
        )->withPivot("primary");
    }

    public function brandLogos()
    {
        return $this->belongsToMany(
            MediaLibrary::class,
            "store_brand_logo_relations",
            "store_id",
            "media_library_id"
        )->withPivot("primary");
    }
}
