<?php

namespace App\Http\Controllers\LoggedIn\MediaLibrary;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team)
    {
        $this->authorize("can-read", $team);
        return Inertia::render("Media/Media");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team)
    {
        $this->authorize("can-create-and-update", $team);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // use and find the Team from request as that is the Team user want to store a Post for
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        dd("request er:", $request->file("images"));
        dd("request er:", $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-read", $team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-create-and-update", $team);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-create-and-update", $team);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-destroy", $team);
    }
}
