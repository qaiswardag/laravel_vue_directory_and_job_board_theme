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

    // public function update(
    //     Request $request,
    //     UpdatesUserProfileInformation $updater
    // ) {
    //     if (config("fortify.lowercase_usernames")) {
    //         $request->merge([
    //             Fortify::username() => Str::lower(
    //                 $request->{Fortify::username()}
    //             ),
    //         ]);
    //     }

    //     $updater->update($request->user(), $request->all());

    //     return app(ProfileInformationUpdatedResponse::class);
    // }

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
}
