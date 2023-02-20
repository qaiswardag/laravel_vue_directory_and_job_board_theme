<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpParser\Node\Expr\AssignOp\Concat;

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
                    ->where("first_name", "LIKE", "%" . $term . "%")
                    ->orWhere("last_name", "LIKE", "%" . $term . "%");
            })
            ->paginate(10);
        // // append users
        $users->appends($request->all());
        //
        return Inertia::render("Superadmin/Users/Index", [
            "users" => $users,
            "results" => $users->total(),
            "search_query" => $request->search_query,
            "selected_category" => $request->selected_category,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
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
    public function destroy(User $user)
    {
        sleep(1);

        $user->delete();

        //
        return redirect()
            ->route("superadmin.users.index")
            ->with("success", "Successfully deleted User with id: {$user->id}");
    }
}
