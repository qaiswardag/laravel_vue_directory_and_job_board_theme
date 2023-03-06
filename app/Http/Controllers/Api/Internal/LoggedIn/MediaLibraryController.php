<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Team $team)
    {
        $this->authorize("can-read", $team);

        $media = $team
            ->media()
            ->latest()
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("name", "LIKE", "%" . $term . "%");
            })
            ->paginate(8);

        //

        // return
        return $media;
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
    public function show(MediaLibrary $mediaLibrary, Team $team)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaLibrary $mediaLibrary, Team $team)
    {
        // testing
        // return response()->json($mediaLibrary->id, 200);
        // return response()->json($team, 200);

        $this->authorize("can-read", $team);

        //

        // return
        return $mediaLibrary;
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
