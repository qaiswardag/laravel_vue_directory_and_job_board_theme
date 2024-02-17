<?php

namespace App\Http\Controllers\Guests\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Store;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreController extends Controller
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
    public function show(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     */
    public function showAPI($teamSlug, $postSlug, $postId)
    {
        // Call the getShow method and store its return values
        list(
            $store,
            $authors,
            $categories,
            $states,
            $storeTeam,
        ) = $this->getShow($postId);

        // Render
        return [
            "postType" => "Store",
            "post" => $store,
            "authors" => $authors,
            "states" => $states,
            "categories" => $categories,
            "team" => $storeTeam,
        ];
    }

    private function getShow($postId)
    {
        $store = Store::where("id", $postId)
            ->where("published", true)
            ->with("coverImages")
            ->firstOrFail();

        $postRenderView = "Guests/Items/SingleItem";

        $authors = $store->authors;
        $categories = $store->categories;
        $states = $store->states;

        $storeTeam = Team::find($store->team_id);

        // Return the values as an array
        return [
            $store,
            $authors,
            $categories,
            $states,
            $storeTeam,
            $postRenderView,
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
