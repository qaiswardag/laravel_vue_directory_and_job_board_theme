<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AttachUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Team $team)
    {
        $this->authorize("can-read", $team);
        // TODO: Ability to add team owner as Author
        // TODO: Filter Role Read out

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        // $users = $team->teamUsers();
        $users = $team
            // ->owner()
            ->teamUsers()
            ->latest()
            // Search informations
            ->when($request->query("search_query"), function (
                $query,
                $searchQuery
            ) {
                $query
                    ->where("email", "LIKE", "%" . $searchQuery . "%")
                    ->orWhere("first_name", "LIKE", "%" . $searchQuery . "%")
                    ->orWhere("first_name", "LIKE", "%" . $searchQuery . "%")
                    ->orWhere(
                        DB::raw("CONCAT(first_name, ' ', last_name)"),
                        "LIKE",
                        "%" . $searchQuery . "%"
                    );
            })
            ->paginate(2);

        // // filter by users name
        // $query = where(function ($subQuery) use ($searchQuery) {
        //     $subQuery
        //         ->where("first_name", "LIKE", "%" . $searchQuery . "%")
        //         ->orWhere("last_name", "LIKE", "%" . $searchQuery . "%")
        //         ->orWhere(
        //             DB::raw("CONCAT(first_name, ' ', last_name)"),
        //             "LIKE",
        //             "%" . $searchQuery . "%"
        //         );
        // });

        return [
            "users" => $users,
            // "results" => $users->total(),
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
