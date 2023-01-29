<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeamControllerRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\CreatesTeams;

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
    public function store(StoreTeamControllerRequest $request, User $user)
    {
        //
        //
        // 1
        // return DB::transaction(function () use ($input) {
        //     return tap(
        //         User::create([
        //             "name" => $input["name"],
        //             "email" => $input["email"],
        //             "password" => Hash::make($input["password"]),
        //         ]),
        //         function (User $user) {
        //             $this->createTeam($user);
        //         }
        //     );
        // });
        //
        //
        // 2
        /**
         * Create a personal team for the user.
         */
        // protected function createTeam(User $user): void
        // {
        //     $user->ownedTeams()->save(
        //         Team::forceCreate([
        //             "user_id" => $user->id,
        //             "name" => explode(" ", $user->name, 2)[0] . "'s Team",
        //             "personal_team" => true,
        //         ])
        //     );
        // }
        //
        //
        //
        //
        //
        //
        DB::transaction(function () use ($request, $user) {
            $team = Team::create([
                "user_id" => $request->user()->id,
                "name" => $request->name,
                "personal_team" => false,
            ]);

            // TODO: update user and set as users current team.
            $user
                ->forceFill([
                    // "current_team_id" => $team->id,
                    "current_team_id" => 7,
                ])
                ->update();
        });

        return redirect()
            ->back()
            ->with("success", "Team have been created successfully.");
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
