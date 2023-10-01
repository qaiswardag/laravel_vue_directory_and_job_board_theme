<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user, Team $team)
    {
        $this->authorize("can-create-and-update", $team);

        return Inertia::render("Profile/CreatePayment/CreatePayment", [
            "team" => $team->load("owner"),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = Team::findOrFail($request->team["id"]);
        $user = User::findOrFail($request->user_id);

        $this->authorize("can-create-and-update", $team);
    }
}
