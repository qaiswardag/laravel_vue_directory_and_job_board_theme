<?php

namespace App\Models;

use App\Models\Post\Post;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string<int, string>
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "username",
        "password",
        "public",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "password",
        "remember_token",
        "two_factor_recovery_codes",
        "two_factor_secret",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "email_verified_at" => "datetime",
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ["profile_photo_url"];

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $number = 100000; // set a default number
            $firstName = $user->first_name;
            $lastName = $user->last_name;

            do {
                // generate a new username using the first name, last name, and number
                $username = Str::lower(
                    Str::slug($firstName . "_" . $lastName . "_" . $number, "_")
                );
                $number++; // increment the number

                // check if the username already exists in the database
            } while (static::where("username", $username)->exists());

            // assign the unique username to the user model
            $user->username = $username;
        });
    }
}
