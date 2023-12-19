<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuperadminSwitchTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->authorize("superadmin-can-create-and-update");

        $validator = Validator::make($request->all(), [
            "team_id" => ["required", "Integer", "max:255"],
        ]);

        if ($validator->fails()) {
            // Handle validation failure
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        $teamId = $request->team_id;

        $teamCheckOwnership = Team::find($teamId);

        $teamUser = TeamUser::where("team_id", $teamId)
            ->when($teamId !== null, function ($query) use ($user) {
                $query->where("user_id", $user->id);
            })
            ->first();

        // if superadmin owns the team
        if ($teamCheckOwnership->user_id === $user->id) {
            $user
                ->forceFill([
                    "current_team_id" => $teamId,
                ])
                ->save();

            return back();
        }

        //
        //
        //
        //

        // if superadmin do not own the team
        if ($teamCheckOwnership->user_id !== $user->id) {
            //

            // first check if superadmin belongs to the team: TeamUser

            //
            //
            // superadmin belong to the team. just switch
            if ($teamUser) {
                $user
                    ->forceFill([
                        "current_team_id" => $teamId,
                    ])
                    ->save();

                return back();
            }

            // superadmin do not own the team and superadmin do not belong to the team
            // create team user relationship for superadmin
            if (!$teamUser) {
                //
                dd(
                    "superadmin do not own the team and superadmin do not belong to the team"
                );
            }
            //
            //
            //
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
