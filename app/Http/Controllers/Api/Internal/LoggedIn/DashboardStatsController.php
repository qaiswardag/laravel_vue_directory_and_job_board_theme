<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show($teamId = null)
    {
        $team = Team::find($teamId);

        $latestMedia = null;
        $latestPosts = null;
        $latestTeamMembers = null;

        if ($team !== null) {
            // Authorize user permissions for the specified team (if the team exists)
            $this->authorize("can-read", $team);

            $latestMedia = MediaLibrary::where("team_id", $team->id)
                ->latest()
                ->take(16)
                ->get();
            $latestPosts = Post::where("team_id", $team->id)
                ->latest()
                ->with("coverImages")
                ->take(16)
                ->get();

            $latestUserIdsWithRoles = TeamUser::where("team_id", $team->id)
                ->latest()
                ->take(16)
                ->pluck("role", "user_id")
                ->toArray();

            $latestTeamMembers = User::whereIn(
                "id",
                array_keys($latestUserIdsWithRoles)
            )
                ->get(["id", "first_name", "last_name", "profile_photo_path"])
                ->map(function ($user) use ($latestUserIdsWithRoles) {
                    $user->role = $latestUserIdsWithRoles[$user->id] ?? null;
                    return $user;
                });
        }

        return [
            "latestMedia" => $latestMedia,
            "latestPosts" => $latestPosts,
            "latestTeamMembers" => $latestTeamMembers,
        ];
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
