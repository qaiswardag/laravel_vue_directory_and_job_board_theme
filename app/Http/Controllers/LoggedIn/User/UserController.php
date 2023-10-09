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

class UserController extends Controller
{
    use ConfirmsTwoFactorAuthentication;

    /**
     * Show the general profile settings screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $this->validateTwoFactorAuthenticationState($request);

        // stripe # start
        $user = Auth::user();

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

        $stripeId = $user->stripe_id;

        // if user is deleted at Stripe
        if ($stripeId) {
            $publishableKey = config("services.stripe.key");
            $intent = $user->createSetupIntent();
        }

        // stripe # end

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
