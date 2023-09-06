<?php

namespace App\Http\Controllers\Superadmin\PageBuilder;

use App\Http\Controllers\Controller;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PageBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("superadmin-can-read");

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $components = PageBuilderComponent::latest();

        $components->when($request->filled("selected_category"), function (
            $query
        ) use ($request, $searchQuery) {
            $selectedCategory = $request->input("selected_category");

            if ($selectedCategory === "id") {
                // Check if search_query is null or empty and selected_category is ID
                if ($searchQuery === null || trim($searchQuery) === "") {
                    // No search query provided, show all results
                    return;
                }

                // Convert $searchQuery to an array of IDs using the explode function
                $idArray = explode(",", $searchQuery);
                // filter by user ID
                $query->whereIn("id", $idArray);
            }

            if ($selectedCategory === "title") {
                // filter by user name
                $query->where(function ($subQuery) use ($searchQuery) {
                    $subQuery->where("title", "LIKE", "%" . $searchQuery . "%");
                });
            }
        });

        $components = $components->paginate(4);

        $components->appends($request->all());

        return Inertia::render("Superadmin/PageBuilder/Components/Index", [
            "components" => $components,
            "results" => $components->total(),
            "oldInput" => [
                "search_query" => $request->input("search_query"),
                "selected_category" => $request->input("selected_category"),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        dd("came here");
        return Inertia::render(
            "Superadmin/PageBuilder/Components/CreateComponent/CreateComponent",
            []
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
