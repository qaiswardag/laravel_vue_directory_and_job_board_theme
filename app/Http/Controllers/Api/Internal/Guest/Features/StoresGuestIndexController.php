<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Store\Store;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreState;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoresGuestIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->category);
        $categoryID = null;
        $stateID = null;

        if (isset($request->category["id"])) {
            $categoryID = $request->category["id"];
        }

        if (isset($request->state["id"])) {
            $stateID = $request->state["id"];
        }

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $categories = StoreCategory::orderBy("name")->get();
        $states = StoreState::orderBy("name")->get();

        $query = Store::latest()
            ->with("team")
            ->with("categories")
            ->with("coverImages")
            ->with("states")
            ->with("authors")
            ->where("published", true)
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            });

        // logic for filtereing based on categories, startes etc.
        if ($categoryID) {
            $query->whereHas("categories", function ($query) use ($categoryID) {
                $query->where("store_categories.id", $categoryID);
            });
        }
        if ($stateID) {
            $query->whereHas("states", function ($query) use ($stateID) {
                $query->where("store_states.id", $stateID);
            });
        }

        $posts = $query->paginate(20);

        $posts->appends($request->all());

        return [
            "categories" => $categories,
            "states" => $states,
            "posts" => $posts,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ];
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
    public function show()
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
    public function destroy(string $id)
    {
        //
    }
}
