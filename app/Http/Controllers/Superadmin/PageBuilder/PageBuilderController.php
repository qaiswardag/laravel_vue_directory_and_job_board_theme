<?php

namespace App\Http\Controllers\Superadmin\PageBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Superadmin\StorePageBuilderComponentRequest;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategory;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategoryRelations;
use App\Models\Superadmin\PageBuilder\PageBuilderCoverImageRelation;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Authorize superadmin
        $this->authorize("superadmin-can-read");

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $query = PageBuilderComponent::orderBy('updated_at', 'desc')
            ->with("categories")
            ->with("coverImages");

        $query->when($request->filled("selected_category"), function (
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

        $components = $query->paginate(12);

        $components->appends($request->all());

        // Post created by
        // Retrieve user information for each post
        foreach ($components as $component) {
            $user = User::find($component->user_id);
            if ($user !== null) {
                $component->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "profile_photo_path" => $user->profile_photo_path,
                ];
            }
            if ($user === null) {
                $component->updatedBy = null;
            }
        }

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
    public function create($teamId)
    {
        // Authorize superadmin
        $this->authorize("superadmin-can-destroy");

        // Conponents can only be managed when a Team is selected, as Team Media Library is needed
        $team = Team::find($teamId);

        if ($team === null || $team->id !== 1) {
            return Inertia::render("Error", [
                "customError" =>
                // Error message for the user.
                self::FORBIDDEN_ADMIN_TEAM_ACTION,
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
    public function store(StorePageBuilderComponentRequest $request)
    {
        $this->authorize("superadmin-can-create-and-update");

        // Conponents can only be created when a Team is selected, as Team Media Library is needed
        $team = Team::find($request->team["id"]);

        if ($team === null || $team->id !== 1) {
            return Inertia::render("Error", [
                "customError" =>
                // Error message for the user.
                self::FORBIDDEN_ADMIN_TEAM_ACTION,
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        $title = $request->title;
        $html_code = $request->html_code;
        $published = $request->published;
        $userId = $request->user_id;

        //
        //
        // Create the post and store it in a variable
        $component = PageBuilderComponent::create([
            "title" => $title,
            "html_code" => $html_code,
            "published" => $published,
            "user_id" => $userId,
        ]);

        $componentId = $component->id;

        // cover images
        if (
            $request->cover_image !== null &&
            is_array($request->cover_image) &&
            count($request->cover_image) !== 0
        ) {
            // Loop through the array and attach each item to the post
            foreach ($request->cover_image as $image) {
                // Check if the "id" key exists in the $image array
                if (array_key_exists("id", $image)) {
                    $imageId = $image["id"];

                    // Check if a media library record with this ID exists
                    $mediaLibrary = MediaLibrary::find($imageId);
                    if ($mediaLibrary) {
                        // Determine the primary status from the pivot
                        $isPrimary = isset($image["pivot"]["primary"])
                            ? $image["pivot"]["primary"]
                            : false;

                        // Create a new record in the table
                        PageBuilderCoverImageRelation::create([
                            "media_library_id" => $imageId,
                            "component_id" => $componentId,
                            "primary" => $isPrimary,
                        ]);
                    }
                }
            }
        }

        // categories
        if (
            $request->categories !== null &&
            is_array($request->categories) &&
            count($request->categories) !== 0
        ) {
            // Loop through the categories array and attach each category to the post
            foreach ($request->categories as $category) {
                // Check if the "id" key exists in the $category array
                if (array_key_exists("id", $category)) {
                    $categoryId = $category["id"];

                    // Check if a category record with this ID exists
                    $categoryModel = PageBuilderComponentCategory::find(
                        $categoryId
                    );

                    if ($categoryModel) {
                        // Create a new record in the PageBuilderComponentCategoryRelations table
                        PageBuilderComponentCategoryRelations::create([
                            "category_id" => $categoryId,
                            "component_id" => $componentId,
                        ]);
                    }
                }
            }
        }

        // Return the current team that the user is on, rather than the team that the user is storing the post for.
        $currentTeam = Auth::user()->currentTeam;

        return redirect()->route("admin.components", $currentTeam);
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
    public function edit($teamId, PageBuilderComponent $component)
    {
        // Authorize superadmin
        $this->authorize("superadmin-can-create-and-update");

        $team = Team::find($teamId);

        // Conponents can only be managed when a Team is selected, as Team Media Library is needed
        if ($team === null || $team->id !== 1) {
            return Inertia::render("Error", [
                "customError" =>
                // Error message for the user.
                self::FORBIDDEN_ADMIN_TEAM_ACTION,
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        // Retrieve items associated with the component
        $categories = $component->categories;
        $coverImages = $component->coverImages;

        return Inertia::render(
            "Superadmin/PageBuilder/Components/UpdateComponent/UpdateComponent",
            [
                "post" => $component,
                "categories" => $categories,
                "coverImages" => $coverImages,
            ]
        );
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request)
    {
        // Authorize superadmin
        $this->authorize("superadmin-can-create-and-update");

        $team = Team::findOrFail($request->teamId);
        $component = PageBuilderComponent::findOrFail($request->componentId);

        // Conponents can only be managed when a Team is selected, as Team Media Library is needed
        if ($team === null || $team->id !== 1) {
            return Inertia::render("Error", [
                "customError" =>
                // Error message for the user.
                self::FORBIDDEN_ADMIN_TEAM_ACTION,
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected
        $this->authorize("can-create-and-update", $team);

        $newComponent = $component->replicate();

        $newComponent->created_at = Carbon::now();
        $newComponent->published = false;
        $newComponent->save();

        return redirect()->route("admin.components", [
            "teamId" => $team->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        StorePageBuilderComponentRequest $request,
        $componentId
    ) {
        $this->authorize("superadmin-can-create-and-update");

        $pageBuilderComponent = PageBuilderComponent::findOrFail($componentId);

        $team = Team::findOrFail($request->team["id"]);

        $userId = $request->user_id;

        // Authorize superadmin

        // Conponents can only be managed when a Team is selected, as Team Media Library is needed
        if ($team === null || $team->id !== 1) {
            return Inertia::render("Error", [
                "customError" =>
                // Error message for the user.
                self::FORBIDDEN_ADMIN_TEAM_ACTION,
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        // Create the post and store it in a variable
        $pageBuilderComponent->update([
            "title" => $request->title,
            "html_code" => $request->html_code,
            "published" => $request->published,
            "user_id" => $userId,
        ]);

        // Get the post ID
        $postId = $pageBuilderComponent->id;

        // Update cover images # start

        // Retrieve the existing cover image relationships for the post
        $existingCoverImages = PageBuilderCoverImageRelation::where(
            "component_id",
            $postId
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingCoverImageIds = $existingCoverImages
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $request->cover_image is not null and is an array
        if (
            $request->cover_image !== null &&
            gettype($request->cover_image) === "array" &&
            count($request->cover_image) !== 0
        ) {
            foreach ($request->cover_image as $coverImage) {
                $imageId = $coverImage["id"];
                $isPrimary = isset($coverImage["pivot"]["primary"])
                    ? $coverImage["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                PageBuilderCoverImageRelation::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "component_id" => $postId,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingCoverImageIds array
                $key = array_search($imageId, $existingCoverImageIds);
                if ($key !== false) {
                    unset($existingCoverImageIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        PageBuilderCoverImageRelation::where("component_id", $postId)
            ->whereIn("media_library_id", $existingCoverImageIds)
            ->delete();

        // Update cover images # end

        //
        //
        //
        //
        //
        //
        // Update categories
        if (
            $request->categories !== null &&
            gettype($request->categories) === "array" &&
            count($request->categories) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = PageBuilderComponentCategoryRelations::where(
                "component_id",
                $postId
            )
                ->pluck("category_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->categories as $category) {
                $categoryId = $category["id"];
                $updatedResourceIds[] = $categoryId;

                // Update or create  record in the table
                PageBuilderComponentCategoryRelations::updateOrCreate([
                    "category_id" => $categoryId,
                    "component_id" => $postId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            PageBuilderComponentCategoryRelations::where(
                "component_id",
                $postId
            )
                ->whereIn("category_id", $resourcesToDelete)
                ->delete();
        }

        // Return the current team that the user is on, rather than the team that the user is storing the post for.
        $currentTeam = Auth::user()->currentTeam;

        return redirect()->route("admin.components", $currentTeam);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($componentId, $teamId)
    {
        // Authorize superadmin
        $this->authorize("superadmin-can-destroy");

        $pageBuilderComponent = PageBuilderComponent::findOrFail($componentId);

        $team = Team::find($teamId);

        // Conponents can only be managed when a Team is selected, as Team Media Library is needed
        if ($team === null || $team->id !== 1) {
            return Inertia::render("Error", [
                "customError" =>
                // Error message for the user.
                self::FORBIDDEN_ADMIN_TEAM_ACTION,
                "status" => 403, // HTTP status code for the response.
            ]);
        }

        $pageBuilderComponent->delete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the item with id: {$pageBuilderComponent->id}."
            );
    }
}
