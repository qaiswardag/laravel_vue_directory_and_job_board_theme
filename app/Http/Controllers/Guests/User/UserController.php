<?php

namespace App\Http\Controllers\Guests\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render("Guests/User/Index", [
            "users" => null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd("came to user controller and store method");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = User::where("username", $username)->first();

        // Hide additional fields
        $user->makeHidden([
            "email",
            "public",
            "id",
            "superadmin",
            "profile_photo_url",
            "public",
            "two_factor_confirmed_at",
            "stripe_id",
            "pm_type",
            "pm_last_four",
            "trial_ends_at",
            "current_team_id",
            "phone",
            "vat_number",
            "tax_id",
            "vat_id",
            "phone_code",
            "postal_code",
            "line1",
            "line2",
            "email_verified_at",
        ]);

        if (!$user) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        return Inertia::render("Guests/User/Show", [
            "userData" => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
