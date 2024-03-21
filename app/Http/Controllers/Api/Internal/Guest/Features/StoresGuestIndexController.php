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
        $tagsOrContent = $request->input("tags_or_content");

        $searchQuery = $request->input("search_query");

        // states filter logic # start
        $stateIDs = [];

        if (isset($request->state) && is_array($request->state)) {
            foreach ($request->state as $state) {
                $stateIDs[] = $state["id"] ?? null;
            }
        }

        // Remove null values from the array
        $stateIDs = array_filter($stateIDs, function ($value) {
            return $value !== null;
        });
        // states filter logic # end

        // categories filter logic # start
        $categoryIDs = [];

        if (isset($request->category) && is_array($request->category)) {
            foreach ($request->category as $category) {
                $categoryIDs[] = $category["id"] ?? null;
            }
        }

        // Remove null values from the array
        $categoryIDs = array_filter($categoryIDs, function ($value) {
            return $value !== null;
        });
        // categories filter logic # end

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $categories = StoreCategory::orderBy("name")->get();
        $states = StoreState::orderBy("name")->get();

        // Do not search with tags or content
        if (!$tagsOrContent) {
            $query = Store::latest()
                ->with("team")
                ->with("categories")
                ->with("coverImages")
                ->with("states")
                ->with("authors")
                ->where("published", true)

                // states filter logic # start
                ->when(!empty($stateIDs), function ($query) use ($stateIDs) {
                    $query->whereHas("states", function ($query) use (
                        $stateIDs
                    ) {
                        $query->whereIn("store_states.id", $stateIDs);
                    });
                })
                // states filter logic # end

                // categories filter logic # start
                ->when(!empty($categoryIDs), function ($query) use (
                    $categoryIDs
                ) {
                    $query->whereHas("categories", function ($query) use (
                        $categoryIDs
                    ) {
                        $query->whereIn("store_categories.id", $categoryIDs);
                    });
                })
                // categories filter logic # end

                // search for title, team name respecting states and categories
                ->when(!$tagsOrContent && $searchQuery, function ($query) use (
                    $searchQuery
                ) {
                    $query->where(function ($titleTeamQuery) use (
                        $searchQuery
                    ) {
                        $titleTeamQuery
                            ->where("title", "LIKE", "%" . $searchQuery . "%")
                            // search for team name
                            ->orWhereHas("team", function ($teamQuery) use (
                                $searchQuery
                            ) {
                                $teamQuery->where(
                                    "name",
                                    "LIKE",
                                    "%" . $searchQuery . "%"
                                );
                            });
                    });
                });
        }

        // Search with tags or content
        if ($tagsOrContent) {
            $query = Store::latest()
                ->with("team")
                ->with("categories")
                ->with("coverImages")
                ->with("states")
                ->with("authors")
                ->where("published", true)

                // states filter logic # start
                ->when(!empty($stateIDs), function ($query) use ($stateIDs) {
                    $query->whereHas("states", function ($query) use (
                        $stateIDs
                    ) {
                        $query->whereIn("store_states.id", $stateIDs);
                    });
                })
                // states filter logic # end

                // categories filter logic # start
                ->when(!empty($categoryIDs), function ($query) use (
                    $categoryIDs
                ) {
                    $query->whereHas("categories", function ($query) use (
                        $categoryIDs
                    ) {
                        $query->whereIn("store_categories.id", $categoryIDs);
                    });
                })
                // categories filter logic # end

                // search for title, team name respecting states and categories
                ->when($searchQuery, function ($query) use ($searchQuery) {
                    $query->where(function ($titleTeamQuery) use (
                        $searchQuery
                    ) {
                        $titleTeamQuery
                            ->where("title", "LIKE", "%" . $searchQuery . "%")

                            ->orWhere("tags", "LIKE", "%" . $searchQuery . "%")
                            ->orWhere(
                                "content",
                                "LIKE",
                                "%" . $searchQuery . "%"
                            )

                            // search for team name
                            ->orWhereHas("team", function ($teamQuery) use (
                                $searchQuery
                            ) {
                                $teamQuery->where(
                                    "name",
                                    "LIKE",
                                    "%" . $searchQuery . "%"
                                );
                            });
                    });
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
