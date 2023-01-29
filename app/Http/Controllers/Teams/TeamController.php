<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeamControllerRequest;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
    public function store(StoreTeamControllerRequest $request)
    {
        //dd($request->name);
        // dd($request->user()->id);
        //
        //
        // $creator = app(CreatesTeams::class);
        // create($request->user(), $request->all());
        //

        sleep(1);
        $team = Team::create([
            "user_id" => $request->user()->id,
            "name" => $request->name,
            "personal_team" => false,
        ]);

        // TODO: update user and set as users current team.
        $request->user()->update([
            "current_team_id" => $team->id,
        ]);

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
