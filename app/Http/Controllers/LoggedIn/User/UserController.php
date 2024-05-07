<?php

namespace App\Http\Controllers\LoggedIn\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Controllers\Inertia\Concerns\ConfirmsTwoFactorAuthentication;
use Laravel\Jetstream\Jetstream;
use Laravel\Cashier\Cashier;

use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Fortify\Fortify;
use Illuminate\Contracts\Auth\StatefulGuard;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Actions\ConfirmPassword;
use Laravel\Jetstream\Contracts\DeletesUsers;

class UserController extends Controller
{
    use ConfirmsTwoFactorAuthentication;

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return \Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse
     */

    public function update(
        Request $request,
        UpdatesUserProfileInformation $updater
    ) {
        if (config("fortify.lowercase_usernames")) {
            $request->merge([
                Fortify::username() => Str::lower(
                    $request->{Fortify::username()}
                ),
            ]);
        }

        if ($request->user()->id === 2 || $request->user()->id === 3) {
            return Inertia::render("Error", [
                "customError" =>
                    "You can not update the default admin users in demo mode.",
                "status" => 403,
            ]);
        }

        $updater->update($request->user(), $request->all());
        return app(ProfileInformationUpdatedResponse::class);
    }

    /**
     * Show the general profile settings screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $this->validateTwoFactorAuthenticationState($request);

        list($intent, $publishableKey) = $this->handleStripeIntegration(
            Auth::user()
        );

        return Jetstream::inertia()->render($request, "Profile/Show", [
            "confirmsTwoFactorAuthentication" => Features::optionEnabled(
                Features::twoFactorAuthentication(),
                "confirm"
            ),
            "sessions" => $this->sessions($request)->all(),
            //
            //
            //
            //
            "intent" => $intent,
            "publishableKey" => $publishableKey,
        ]);
    }

    /**
     * Handle Stripe Integration.
     *
     */
    private function handleStripeIntegration($user)
    {
        $intent = null;
        $publishableKey = null;
        $stripeCustomer = null;
        $stripeId = $user->stripe_id;

        if ($stripeId) {
            $stripeCustomer = $user->asStripeCustomer() ?? null;
        }

        // if user is deleted at Stripe
        if ($stripeCustomer && $stripeCustomer->isDeleted()) {
            $user
                ->forceFill([
                    "stripe_id" => null,
                    "trial_ends_at" => null,
                    "pm_type" => null,
                    "pm_last_four" => null,
                ])
                ->save();
        }

        if ($stripeId) {
            $publishableKey = config("services.stripe.key");
            $intent = $user->createSetupIntent();
        }

        return [$intent, $publishableKey];
    }

    /**
     * Get the current sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function sessions(Request $request)
    {
        if (config("session.driver") !== "database") {
            return collect();
        }

        return collect(
            DB::connection(config("session.connection"))
                ->table(config("session.table", "sessions"))
                ->where("user_id", $request->user()->getAuthIdentifier())
                ->orderBy("last_activity", "desc")
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                "agent" => [
                    "is_desktop" => $agent->isDesktop(),
                    "platform" => $agent->platform(),
                    "browser" => $agent->browser(),
                ],
                "ip_address" => $session->ip_address,
                "is_current_device" =>
                    $session->id === $request->session()->getId(),
                "last_active" => Carbon::createFromTimestamp(
                    $session->last_activity
                )->diffForHumans(),
            ];
        });
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($session)
    {
        return tap(new Agent(), function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    /**
     * Delete the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, StatefulGuard $guard)
    {
        $user = Auth::user();

        if ($user->id === 1 || $user->id === 2 || $user->id === 3) {
            return Inertia::render("Error", [
                "customError" => "You cannot delete the default admin users.",
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->password
        );

        if (!$confirmed) {
            throw ValidationException::withMessages([
                "password" => __("The password is incorrect."),
            ]);
        }

        // User delete media library folder # start
        $userImagesFolder = File::exists(
            storage_path("app/public/uploads/" . "user" . "_" . $user->id)
        );

        $path = storage_path("app/public/uploads/" . "user" . "_" . $user->id);

        // Check if the directory exists in storage
        if ($userImagesFolder) {
            // Delete the directory from storage
            File::deleteDirectory($path);
        } else {
            Log::error(
                "An error occurred while deleting the images folder for the user."
            );
        }
        // User delete media library folder # end

        // Delete Team image folder # start
        foreach ($user->ownedTeams as $team) {
            $teamId = $team->id;

            $teamImagesFolder = File::exists(
                storage_path("app/public/uploads/" . $teamId)
            );

            $path = storage_path("app/public/uploads/" . $teamId);

            // Check if the directory exists in storage
            if ($teamImagesFolder) {
                // Delete the directory from storage
                File::deleteDirectory($path);
            } else {
                Log::error(
                    "An error occurred while deleting the images folder for team with ID: {$teamId}."
                );
            }
        }

        // Delete Team image folder # start

        app(DeletesUsers::class)->delete($request->user()->fresh());

        $guard->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(url("/"));
    }
}
