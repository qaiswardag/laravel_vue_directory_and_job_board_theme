<?php

namespace App\Http\Controllers\Superadmin\PageBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Superadmin\StorePageBuilderComponentRequest;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Team;
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
    public function create($referenceId)
    {
        $this->authorize("superadmin-can-create-and-update");

        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" =>
                    // Error message for the user.
                    "Only team members who belong to Team with ID 1 can create or update components.",
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        return Inertia::render(
            "Superadmin/PageBuilder/Components/CreateComponent/CreateComponent",
            []
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StorePageBuilderComponentRequest $request,
        $referenceId
    ) {
        dd("this is store method");

        $this->authorize("superadmin-can-create-and-update");

        //
        //
        //

        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" =>
                    // Error message for the user.
                    "Only team members who belong to Team with ID 1 can create or update components.",
                "status" => 403, // HTTP status code for the response.
            ]);
        }
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
    public function edit($referenceId, PageBuilderComponent $component)
    {
        $this->authorize("superadmin-can-create-and-update");
        //

        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" =>
                    // Error message for the user.
                    "Only team members who belong to Team with ID 1 can create or update components.",
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        //
        // Retrieve the categories associated with the component
        $categories = $component->categories; // Assuming you have a relationship set up

        //
        //
        return Inertia::render(
            "Superadmin/PageBuilder/Components/UpdateComponent/UpdateComponent",
            [
                "component" => $component,
                "categories" => $categories,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        StorePageBuilderComponentRequest $request,
        PageBuilderComponent $pageBuilderComponent,
        $referenceId
    ) {
        $this->authorize("superadmin-can-create-and-update");
        //

        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" =>
                    // Error message for the user.
                    "Only team members who belong to Team with ID 1 can create or update components.",
                "status" => 403, // HTTP status code for the response.
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        PageBuilderComponent $pageBuilderComponent,
        $referenceId
    ) {
        $this->authorize("superadmin-can-destroy");
        //
        //
        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" =>
                    // Error message for the user.
                    "Only team members who belong to Team with ID 1 can create or update components.",
                "status" => 403, // HTTP status code for the response.
            ]);
        }
    }
}
