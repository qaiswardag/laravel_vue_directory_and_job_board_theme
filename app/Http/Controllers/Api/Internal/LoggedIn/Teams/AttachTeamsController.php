<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachTeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Support\Collection
     */

    public function index(Request $request)
    {
        $user = Auth::user();

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $teams0 = Team::where(function ($query) use ($user) {
            $query
                ->where("user_id", $user->id)
                ->orWhereHas("users", function ($subQuery) use ($user) {
                    $subQuery->where("users.id", $user->id);
                });
        })
            ->orderBy("name")
            ->when($request->query("search_query"), function (
                $query,
                $searchQuery
            ) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where("name", "LIKE", "%" . $searchQuery . "%");
                });
            })
            ->paginate(10);

        //
        //
        //
        //
        //
        //
        // $teams1 = $user
        //     ->teams()
        //     ->when($request->query("search_query"), function (
        //         $query,
        //         $searchQuery
        //     ) {
        //         $query->where(function ($query) use ($searchQuery) {
        //             $query->where("name", "LIKE", "%" . $searchQuery . "%");
        //         });
        //     })
        //     ->paginate(2);

        // $teams2 = $user
        //     ->ownedTeams()
        //     ->when($request->query("search_query"), function (
        //         $query,
        //         $searchQuery
        //     ) {
        //         $query->where(function ($query) use ($searchQuery) {
        //             $query->where("name", "LIKE", "%" . $searchQuery . "%");
        //         });
        //     })
        //     ->paginate(2);

        return [
            "teams" => $teams0,
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
