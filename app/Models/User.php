<?php

namespace App\Models;

use App\Models\Post\Post;
use App\Models\Superadmin\Superadmin;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use function Illuminate\Events\queueable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;

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

        "country",
        "city",
        "postal_code",
        "phone",
        "job_title",
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

    protected $with = ["superadmin"];

    public function superadmin()
    {
        return $this->hasOne(Superadmin::class, "user_id");
    }

    /**
     * Generate a unique username for the model.
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
     * a unique username for the new team instance.
     *
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $number = 1; // set a default number
            $firstName = $user->first_name;
            $lastName = $user->last_name;

            // generate a new username using the first name and last name
            $username = Str::lower(
                Str::slug($firstName . "_" . $lastName, "_")
            );

            // check if the username already exists in the database
            while (static::where("username", $username)->exists()) {
                // If the username exists, add the number suffix and check again
                $username = Str::lower(
                    Str::slug($firstName . "_" . $lastName . "_" . $number, "_")
                );
                $number++; // increment the number
            }

            // assign the unique username to the user model
            $user->username = $username;
        });
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::updated(
            queueable(function (User $customer) {
                $name = $customer->first_name . " " . $customer->last_name;
                $email = $customer->email;
                $country = $customer->country;
                $city = $customer->city;
                $postalCode = $customer->postal_code;

                $phoneCode = $customer->phone_code ?? null;
                $phone = $customer->phone ?? null;

                if ($phoneCode && $phone) {
                    $phoneCodePlusPhone = $phoneCode . $phone;
                }

                $stripeCustomer = null;

                if ($customer->hasStripeId()) {
                    $stripeCustomer = $customer->asStripeCustomer() ?? null;
                }

                if ($stripeCustomer && $stripeCustomer->isDeleted()) {
                    $customer
                        ->forceFill([
                            "stripe_id" => null,
                            "trial_ends_at" => null,
                            "pm_type" => null,
                            "pm_last_four" => null,
                        ])
                        ->save();
                }

                if ($customer->hasStripeId()) {
                    $customer->updateStripeCustomer([
                        "name" => $name,
                        "email" => $email,
                        "phone" => $phone,
                        "address" => [
                            "country" => $country,
                            "city" => $city,
                            "postal_code" => $postalCode,
                        ],
                    ]);
                }
            })
        );
    }
}
