<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Team $team)
    {
        $this->authorize("can-read", $team);
        $searchQuery = "";
        $currentClickedPage = "";

        $totalResults = $team
            ->media()

            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("name", "LIKE", "%" . $term . "%");
            })
            ->count();

        $media = $team
            ->media()
            ->latest()
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("name", "LIKE", "%" . $term . "%");
            })
            ->paginate(24);

        // check for search_query
        if ($request->search_query !== null) {
            $searchQuery = $request->search_query;
        }
        // check for current page
        if ($request->page !== null) {
            $currentClickedPage = $request->page;
        }

        // return
        return [
            "media" => $media,
            "search_query" => $searchQuery,
            "current_clicked_page" => $currentClickedPage,
            "total_results" => $totalResults,
        ];
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
        $this->authorize("can-read", $team);

        $uploadedBy = User::findOrFail($mediaLibrary->user_id);
        $uploadedBy = [
            "firstName" => $uploadedBy->first_name,
            "lastName" => $uploadedBy->last_name,
        ];

        // return
        return [
            "mediaLibrary" => $mediaLibrary,
            "uploadedBy" => $uploadedBy,
        ];
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
