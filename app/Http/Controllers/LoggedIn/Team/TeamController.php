<?php

namespace App\Http\Controllers\LoggedIn\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Team\StoreTeamUpdateRequest;
use App\Http\Requests\Teams\StoreTeamControllerRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class TeamController extends Controller
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
        $creator = app(CreatesTeams::class);

        $team = $creator->create($request->user(), $request->all());

        return redirect()
            ->route("team.update.information", [
                "referenceId" => $team->load("owner")->reference_id,
            ])
            ->with("success", "Team have been successfully created.");
    }
    /**
     * Show the team management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Inertia\Response
     */
    public function show(Request $request, $referenceId)
    {
        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        Gate::authorize("view", $team);

        return Jetstream::inertia()->render($request, "Teams/Show", [
            "team" => $team->load("owner", "users", "teamInvitations"),
            "availableRoles" => array_values(Jetstream::$roles),
            "availablePermissions" => Jetstream::$permissions,
            "defaultPermissions" => Jetstream::$defaultPermissions,
            "permissions" => [
                "canAddTeamMembers" => Gate::check("addTeamMember", $team),
                "canDeleteTeam" => Gate::check("delete", $team),
                "canRemoveTeamMembers" => Gate::check(
                    "removeTeamMember",
                    $team
                ),
                "canUpdateTeam" => Gate::check("update", $team),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($referenceId)
    {
        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        Gate::authorize("view", $team);

        return Inertia::render(
            "Teams/UpdateTeamInformation/UpdateTeamInformation",
            [
                "team" => $team->load("owner"),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTeamUpdateRequest $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(UpdatesTeamNames::class)->update(
            $request->user(),
            $team,
            $request->all()
        );

        return redirect()->route("teams.show", [
            "referenceId" => $team->reference_id,
        ]);
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
