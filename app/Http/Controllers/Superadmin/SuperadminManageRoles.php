<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Superadmin\Superadmin;
use App\Models\User;
use Illuminate\Http\Request;

class SuperadminManageRoles extends Controller
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
    public function create(Request $request, User $user)
    {
        $this->authorize("superadmin-can-destroy");

        $input = $request->all(); // Get all input data
        Validator::make($input, [
            "role" => [
                "required",
                "string",
                "max:255",
                "in:admin,editor,reader",
            ],
            "user_id" => ["required", "integer", "unique:superadmins,user_id"],
        ])->validateWithBag("createSuperadmin");

        Superadmin::create([
            "role" => $request->role,
            "user_id" => $user->id,
        ]);

        return back()->with(
            "success",
            "{$user->first_name} {$user->last_name} have been created as Superadmin with role as {$request->role}."
        );
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
    public function update(Request $request, User $user)
    {
        $this->authorize("superadmin-can-destroy");

        $input = $request->all(); // Get all input data
        Validator::make($input, [
            "role" => [
                "required",
                "string",
                "max:255",
                "in:admin,editor,reader",
            ],
            "user_id" => ["required", "integer"],
        ])->validateWithBag("createSuperadmin");

        sleep(1);

        Superadmin::where("user_id", $user->id)->update([
            "role" => $request->role,
        ]);

        return back()->with(
            "success",
            "{$user->first_name} {$user->last_name} have been updated as Superadmin with role as {$request->role}."
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize("superadmin-can-destroy");

        $input = $request->all(); // Get all input data
        Validator::make($input, [
            "user_id" => ["required", "integer"],
        ])->validateWithBag("createSuperadmin");

        Superadmin::where("user_id", $user->id)->delete();

        return back()->with(
            "success",
            "{$user->first_name} {$user->last_name} have been removed as Superadmin."
        );
    }
}
