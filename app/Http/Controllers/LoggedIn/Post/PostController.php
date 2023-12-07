<?php

namespace App\Http\Controllers\LoggedIn\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Post\StorePostRequest;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use App\Models\Post\PostCategoryRelation;
use App\Models\Post\PostCoverImageRelation;
use App\Models\Post\PostStoreRelation;
use App\Models\Store\Store;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        // Authorize the team that the user has selected to store the post for, rather than the team that the user is currently on.
        $this->authorize("can-read", $team);

        $posts = $team
            ->posts()
            ->with("coverImages")
            ->with("categories")
            ->with(["stores" => function ($query) {
                $query->with("states");
            }])
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->where(function ($query) {
                $query
                    ->where('published', true)
                    ->where(function ($query) {
                        $query
                            ->where('ended_at', '>=', Carbon::now())
                            ->orWhereNull('ended_at');
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $posts->appends($request->all());

        // Post created by
        // Retrieve user information for each post
        foreach ($posts as $post) {
            $user = User::find($post->user_id);
            if ($user !== null) {
                $post->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "job_title" => $user->job_title,
                    "profile_photo_path" => $user->profile_photo_path,
                ];
            }
            if ($user === null) {
                $post->updatedBy = null;
            }
        }

        return Inertia::render("Posts/Index", [
            "posts" => $posts,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($teamId)
    {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-create-and-update", $team);

        return Inertia::render("Posts/CreatePost/CreatePost");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        // Find the current team that the user is on, rather than the team that the user is storing the post for.
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        $title = $request->title;
        $startedAt = $request->started_at;
        $endedAt = $request->ended_at;
        $content = $request->content;
        $userId = $request->user_id;

        // slug
        $slug = Str::lower(Str::slug($request->slug, "_"));

        // Create the post and store it in a variable
        $post = Post::create([
            "user_id" => $userId,
            "team_id" => $team->id,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,
            "started_at" => $startedAt,
            "ended_at" => $endedAt,
            "days_before_campaign_visibility" => $request->days_before_campaign_visibility,
            "content" => $content,
            "tags" => $request->tags,
        ]);

        $postId = $post->id;


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
                        PostCoverImageRelation::create([
                            "media_library_id" => $imageId,
                            "post_id" => $postId,
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
                    $categoryModel = PostCategory::find($categoryId);

                    if ($categoryModel) {
                        // Create a new record in the PostCategoryRelation table
                        PostCategoryRelation::create([
                            "category_id" => $categoryId,
                            "post_id" => $postId,
                        ]);
                    }
                }
            }
        }

        // stores
        if (
            $request->stores !== null &&
            is_array($request->stores) &&
            count($request->stores) !== 0
        ) {
            // Loop through the stores array and attach each store to the post
            foreach ($request->stores as $store) {
                // Check if the "id" key exists in the $store array
                if (array_key_exists("id", $store)) {
                    $storeId = $store["id"];

                    // Check if a store record with this ID exists
                    $storeModel = Store::find($storeId);

                    if ($storeModel) {
                        // Create a new record in the table
                        PostStoreRelation::create([
                            "store_id" => $storeId,
                            "post_id" => $postId,
                        ]);
                    }
                }
            }
        }

        if (!$request->published) {
            return redirect()->route("team.posts.index.draft", [
                "teamId" => $team->id,
            ]);
        }
        if ($request->published) {
            return redirect()->route("team.posts.index", [
                "teamId" => $team->id,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($teamId, $slug, $postId)
    {
        $postRenderView = "Posts/Show/ShowTeamPost";

        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        // Retrieve the post, including soft-deleted posts
        $post = Post::withTrashed()->with('coverImages')->findOrFail($postId);

        // Retrieve the user associated with the post
        $user = User::find($post->user_id);

        // Update the $post array with updatedBy information
        if ($user !== null) {
            $post->updatedBy = [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "profile_photo_path" => $user->profile_photo_path,
            ];
        }
        if ($user === null) {
            $post->updatedBy = null;
        }

        $categories = $post->categories;

        $stores = $post
            ->stores()
            ->with('states')
            ->with("coverImages")
            ->get();

        $postTeam = Team::find($post->team_id);

        // Render the post
        return Inertia::render($postRenderView, [
            "post" => $post,
            "categories" => $categories,
            "stores" => $stores,
            "team" => $postTeam,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($teamId, Post $post)
    {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected to store the post for, rather than the team that the user is currently on.
        $this->authorize("can-create-and-update", $team);


        $coverImages = $post->coverImages;
        $categories = $post->categories;
        $stores = $post->stores;

        return Inertia::render("Posts/UpdatePost/UpdatePost", [
            "post" => $post,
            "coverImages" => $coverImages,
            "categories" => $categories,
            "stores" => $stores,
        ]);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request)
    {
        $team = Team::findOrFail($request->teamId);
        $post = Post::findOrFail($request->postId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected
        $this->authorize("can-create-and-update", $team);


        $newPost = null;

        try {
            DB::transaction(function () use ($post, &$newPost) {
                // replicate new post # start
                $newPost = $post->replicate();
                $newPost->save();
                $newPost->update([
                    "title" => $newPost->title . " " . "copy",
                    "is_paid" => null,
                    "paid_at" => null,
                    "started_at" => Carbon::now(),
                    "ended_at" => Carbon::now(),
                    "published" => false,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]);
                // replicate new post # end



                // replicate new categories # start
                if ($post->categories !== null) {
                    foreach ($post->categories as $category) {
                        // Create a new instance of the pivot model
                        $newCategoriesPivotData = new PostCategoryRelation([
                            'post_id' => $newPost->id,
                            'category_id' => $category->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newCategoriesPivotData->save();
                    }
                }
                // replicate new categories # end

                // replicate new stores # start
                if ($post->stores !== null) {
                    foreach ($post->stores as $store) {
                        // Create a new instance of the pivot model
                        $newStoresPivotData = new PostStoreRelation([
                            'post_id' => $newPost->id,
                            'store_id' => $store->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newStoresPivotData->save();
                    }
                }
                // replicate new stores # end

                // replicate new cover images # start
                if ($post->coverImages !== null) {
                    foreach ($post->coverImages as $coverImage) {
                        // Create a new instance of the pivot model
                        $newCoverImagePivotData = new PostCoverImageRelation([
                            'post_id' => $newPost->id,
                            'media_library_id' => $coverImage->id,
                            "primary" => $coverImage->pivot->primary ?? null,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newCoverImagePivotData->save();
                    }
                }
                // replicate new cover images # start

            });
        } catch (Exception $e) {
            Log::error(
                "Oops! Something went wrong. {$e->getMessage()}."
            );

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG, // Error message for the user.
                "status" => 422, // HTTP status code for the response.
            ]);
        }

        if ($newPost !== null) {
            return redirect()->route("team.posts.index", [
                "teamId" => $team->id,
            ]);
        }

        return Inertia::render("Error", [
            "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG,
            "status" => 422,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Post $post)
    {
        // Find the current team that the user is on, rather than the team that the user is storing the post for.
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        $slug = $request->slug;

        $title = $request->title;
        $content = $request->content;
        $teamId = $request->team["id"];
        $userId = $request->user_id;

        $startedAt = $request->started_at;
        $endedAt = $request->ended_at;

        // Create the post and store it in a variable
        $post->update([
            "user_id" => $userId,
            "team_id" => $teamId,
            "started_at" => $startedAt,
            "ended_at" => $endedAt,
            "days_before_campaign_visibility" => $request->days_before_campaign_visibility,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,
            "content" => $content,
            "tags" => $request->tags,
        ]);

        // Get the post ID
        $postId = $post->id;


        // Retrieve the existing cover image relationships for the post
        $existingCoverImages = PostCoverImageRelation::where(
            "post_id",
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
                PostCoverImageRelation::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "post_id" => $postId,
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
        PostCoverImageRelation::where("post_id", $postId)
            ->whereIn("media_library_id", $existingCoverImageIds)
            ->delete();

        // Update cover images # end

        // Update categories
        if (
            $request->categories !== null &&
            gettype($request->categories) === "array" &&
            count($request->categories) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = PostCategoryRelation::where(
                "post_id",
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
                PostCategoryRelation::updateOrCreate([
                    "category_id" => $categoryId,
                    "post_id" => $postId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            PostCategoryRelation::where("post_id", $postId)
                ->whereIn("category_id", $resourcesToDelete)
                ->delete();
        }
        // Update stores relations
        if (
            $request->stores !== null &&
            gettype($request->stores) === "array" &&
            count($request->stores) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = PostStoreRelation::where(
                "post_id",
                $postId
            )
                ->pluck("store_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->stores as $store) {
                $storeId = $store["id"];
                $updatedResourceIds[] = $storeId;

                // Update or create  record in the table
                PostStoreRelation::updateOrCreate([
                    "store_id" => $storeId,
                    "post_id" => $postId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            PostStoreRelation::where("post_id", $postId)
                ->whereIn("store_id", $resourcesToDelete)
                ->delete();
        }

        if (!$request->published) {
            return redirect()->route("team.posts.index.draft", [
                "teamId" => $team->id,
            ]);
        }
        if ($request->published) {
            return redirect()->route("team.posts.index", [
                "teamId" => $team->id,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Team $team)
    {
        $this->authorize("can-destroy", $team);

        $post->delete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the Post with id: {$post->id}."
            );
    }
}
