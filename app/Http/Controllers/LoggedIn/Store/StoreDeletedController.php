<?php

namespace App\Http\Controllers\LoggedIn\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\LoggedIn\Store\StoreStoreRequest;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Store\AuthorStore;
use App\Models\Store\Store;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreCategoryRelation;
use App\Models\Store\StoreCoverImageRelation;
use App\Models\Store\StoreState;
use App\Models\Store\StoreStateRelation;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SoftDeletes;


class StoreDeletedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $teamId)
    {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        // Authorize the team that the user has selected to store the store for, rather than the team that the user is currently on.
        $this->authorize("can-read", $team);

        $stores = $team
            ->stores()
            ->onlyTrashed()
            ->with("coverImages")
            ->with("states")
            ->with("categories")
            ->with("authors")
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $stores->appends($request->all());

        // Store created by
        // Retrieve user information for each store
        foreach ($stores as $store) {
            $user = User::find($store->user_id);
            if ($user !== null) {
                $store->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "job_title" => $user->job_title,
                    "profile_photo_path" => $user->profile_photo_path,
                ];
            }
            if ($user === null) {
                $store->updatedBy = null;
            }
        }

        return Inertia::render("Stores/IndexTrash", [
            "posts" => $stores,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ]);
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
    public function restore($storeId, Team $team)
    {
        $this->authorize("can-create-and-update", $team);


        $store = Store::withTrashed()->findOrFail($storeId);

        $store->restore();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully restored."
            );
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
    public function destroy($storeId, Team $team)
    {
        $this->authorize("can-destroy", $team);

        $store = Store::withTrashed()->findOrFail($storeId);

        $store->forceDelete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the Store."
            );
    }
}
