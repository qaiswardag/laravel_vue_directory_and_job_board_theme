<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
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

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teamMembers()
    {
        $team = Auth::user()->currentTeam;

        return Inertia::render("Teams/TeamMembers/TeamMembers", [
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
    public function store(Request $request, User $user)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $team = Auth::user()->currentTeam;

        return Inertia::render(
            "Teams/UpdateTeamInformation/UpdateTeamInformation",
            [
                "team" => $team->load("owner", "users", "teamInvitations"),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDeleteForm()
    {
        $team = Auth::user()->currentTeam;

        return Inertia::render("Teams/TeamDelete/TeamDelete", [
            "team" => $team->load("owner", "users", "teamInvitations"),
        ]);
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
