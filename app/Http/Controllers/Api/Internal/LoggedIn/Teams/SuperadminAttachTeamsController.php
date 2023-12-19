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
    public function index(Request $request)
    {
        $this->authorize("superadmin-can-read");

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $teams = Team::orderBy("name")
            ->when($request->query("search_query"), function (
                $query,
                $searchQuery
            ) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where("name", "LIKE", "%" . $searchQuery . "%");
                });
            })
            ->paginate(10);

        return [
            "teams" => $teams,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
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
