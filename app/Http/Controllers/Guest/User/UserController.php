<?php

namespace App\Http\Controllers\Guest\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TO DO: show only users which status is not privat
        $users = User::latest()
            ->when($request->query("search_query"), function ($query, $term) {
                $query
                    ->where("title", "LIKE", "%" . $term . "%")
                    ->orWhere("content", "LIKE", "%" . $term . "%");
            })
            ->select("first_name", "last_name", "username")

            ->paginate(10);
        // // append users
        $users->appends($request->all());
        //
        return Inertia::render("Guests/User/Index", [
            "users" => $users,
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
        $username = urldecode($username);

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return Inertia::render("Error", [
                "customError" =>
                    "The URL can only consist of letters, numbers, and underscores. Special characters are not allowed.",
                "status" => 404,
            ]);
        }

        $user = User::where("username", $username)->firstOrFail();

        // TO DO: show only user which status is not privat
        return Inertia::render("Guests/User/Show", [
            "user" => $user,
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
