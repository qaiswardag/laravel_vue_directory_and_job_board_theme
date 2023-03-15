<?php

namespace App\Http\Controllers\Guest\User;

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
        $users = User::latest()
            ->when($request->query("search_query"), function ($query, $term) {
                $query
                    ->where("title", "LIKE", "%" . $term . "%")
                    ->orWhere("content", "LIKE", "%" . $term . "%");
            })
            ->when(
                Auth::user() && Auth::user()->superadmin === 1,
                function ($query) {
                    // if the logged-in user is a superadmin, include all users
                    return $query;
                },
                function ($query) {
                    // if the logged-in user is not a superadmin, exclude users with public = 0
                    return $query->where(function ($query) {
                        $query
                            ->where("public", "!=", 0)
                            ->orWhere("id", "=", Auth::id()); // include the current user
                    });
                }
            )
            ->select("first_name", "last_name", "username", "public")
            ->paginate(10);

        $users->appends($request->all());

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

        $user = User::where("username", $username)
            ->when(
                Auth::user() && Auth::user()->superadmin === 1,
                function ($query) {
                    // if the logged-in user is a superadmin, include all users
                    return $query;
                },
                function ($query) {
                    // if the logged-in user is not a superadmin, exclude users with public = 0
                    return $query->where(function ($query) {
                        $query
                            ->where("public", "!=", 0)
                            ->orWhere("id", "=", Auth::id()); // include the current user
                    });
                }
            )
            ->firstOrFail();

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
