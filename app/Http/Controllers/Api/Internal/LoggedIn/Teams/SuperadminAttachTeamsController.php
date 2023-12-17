<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class SuperadminAttachTeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        sleep(2);
        return response()->json("En fejl opstod.", 401);
        return "Kom til super admin og alle teams i app!";
        //
        //
        //
        //
        //
        //
        //
        //
        $teams = Team::paginate(2);

        return $teams;
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
