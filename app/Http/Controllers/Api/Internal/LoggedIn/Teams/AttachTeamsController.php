<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\Teams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachTeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        sleep(2);
        return response()->json("En fejl opstod.", 401);
        return "Kom til alle users teams";

        //
        //
        //
        //
        //
        $user = Auth::user();

        $teams = $user->$user->allTeams();

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
