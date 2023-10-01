<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Team $team)
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
        dd("cam to store");
        //
        // $this->authorize("can-create-and-update", $team);
    }
}
