<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $this->authorize("superadmin");

        $users = User::latest();

        $users->when($request->filled("selected_category"), function (
            $query
        ) use ($request) {
            $selectedCategory = $request->input("selected_category");

            if ($selectedCategory === "id") {
                // filter by user ID
                $query->where("id", $request->input("search_query"));
            }

            if ($selectedCategory === "name") {
                // filter by user name
                $query->where(function ($subQuery) use ($request) {
                    $subQuery
                        ->where(
                            "first_name",
                            "LIKE",
                            "%" . $request->input("search_query") . "%"
                        )
                        ->orWhere(
                            "last_name",
                            "LIKE",
                            "%" . $request->input("search_query") . "%"
                        )
                        ->orWhere(
                            DB::raw("CONCAT(first_name, ' ', last_name)"),
                            "LIKE",
                            "%" . $request->input("search_query") . "%"
                        );
                });
            }

            if ($selectedCategory === "email") {
                // filter by user email
                $query->where(
                    "email",
                    "LIKE",
                    "%" . $request->input("search_query") . "%"
                );
            }
        });

        $users = $users->paginate(10);
        // append users
        $users->appends($request->all());

        return Inertia::render("Superadmin/Users/Index", [
            "users" => $users,
            "results" => $users->total(),
            "oldInput" => [
                "search_query" => $request->search_query,
                "selected_category" => $request->selected_category,
            ],
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
        $this->authorize("superadmin");

        sleep(1);

        $user->delete();

        //
        return redirect()
            ->route("admin.users")
            ->with("success", "Successfully deleted User with id: {$user->id}");
    }
}
