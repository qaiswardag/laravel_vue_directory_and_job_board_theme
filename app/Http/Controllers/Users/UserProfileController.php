<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

use Concerns\ConfirmsTwoFactorAuthentication;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //  public function show(Request $request)
    //  {
    //      $this->validateTwoFactorAuthenticationState($request);

    //      return Jetstream::inertia()->render($request, 'Profile/Show', [
    //          'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
    //          'sessions' => $this->sessions($request)->all(),
    //      ]);
    //  }

    public function showSessions(Request $request)
    {
        return Jetstream::Inertia()->render(
            $request,
            "Profile/ProfileSecurity/ProfileSecurity",
            [
                "confirmsTwoFactorAuthentication" => Features::optionEnabled(
                    Features::twoFactorAuthentication(),
                    "confirm"
                ),

                "sessions" => $this->sessions($request)->all(),
            ]
        );
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
