<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $owner = $team->owner()->first();

        $owner = collect($owner)->only([
            "id",
            "first_name",
            "last_name",
            "email",
            "username",
            "profile_photo_path",
            "profile_photo_url",
        ]);

        $users = User::query()
            ->join("team_user", function ($join) use ($team) {
                $join
                    ->on("users.id", "=", "team_user.user_id")
                    ->where("team_user.team_id", "=", $team->id);
            })
            ->join("teams", "team_user.team_id", "=", "teams.id")
            ->where("team_user.role", "<>", "reader")
            ->when($request->query("search_query"), function (
                $query,
                $searchQuery
            ) {
                $query->where(function ($query) use ($searchQuery) {
                    $query
                        ->where("email", "LIKE", "%" . $searchQuery . "%")
                        ->orWhere(
                            "first_name",
                            "LIKE",
                            "%" . $searchQuery . "%"
                        )
                        ->orWhere("last_name", "LIKE", "%" . $searchQuery . "%")
                        ->orWhere(
                            DB::raw('CONCAT(first_name, " ", last_name)'),
                            "LIKE",
                            "%" . $searchQuery . "%"
                        );
                });
            })
            ->whereNotExists(function ($query) {
                $query
                    ->select(DB::raw(1))
                    ->from("team_user")
                    ->whereColumn("users.id", "team_user.user_id")
                    ->where("team_user.role", "reader");
            })
            ->select(
                "users.id",
                "users.first_name",
                "users.last_name",
                "users.username",
                "users.email",
                "users.profile_photo_path",
                "team_user.role"
            )
            ->paginate(12);

        //
        //
        if ($users->currentPage() === 1) {
            $ownerDetails = collect([
                "id" => $owner["id"],
                "first_name" => $owner["first_name"],
                "last_name" => $owner["last_name"],
                "username" => $owner["username"],
                "email" => $owner["email"],
                "role" => "owner",
                "profile_photo_path" => $owner["profile_photo_path"],
                "profile_photo_url" => $owner["profile_photo_url"],
            ]);

            $users->prepend($ownerDetails);
        }
        //
        $count = $users->total() + 1; // 1 for the team owner

        //

        return [
            "users" => $users,
            "count" => $count,
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
