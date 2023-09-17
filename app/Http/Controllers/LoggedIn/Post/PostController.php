<?php

namespace App\Http\Controllers\LoggedIn\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Post\StorePostRequest;
use App\Models\Job\JobCategory;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\AuthorPost;
use App\Models\Post\Post;
use App\Models\Post\PostCategoryRelation;
use App\Models\post\PostCoverImageRelation;
use App\Models\Team;
use App\Models\User;
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
            ->with("authors")
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->latest()
            ->paginate(5);

        $posts->appends($request->all());

        // Post created by
        // Retrieve user information for each post
        foreach ($posts as $post) {
            $user = User::find($post->user_id);
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
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        $postId = $post->id;

        // authors
        // Check if the "show_author" property of the $request object is true
        // and the "author" property of the $request object is not null
        if (
            $request->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Loop through the authors array and attach each author to the post
            foreach ($request->author as $author) {
                $authorId = $author["id"];

                // Check if a user record with this ID exists
                $userModel = User::find($authorId);

                if ($userModel) {
                    // Create a new record in the author_post table
                    AuthorPost::create([
                        "user_id" => $authorId,
                        "post_id" => $postId,
                    ]);
                }
            }
        }

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

                        // Create a new record in the StoreCoverImageRelation table
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
                    $categoryModel = JobCategory::find($categoryId);

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

        // Return the current team that the user is on, rather than the team that the user is storing the post for.
        $currentTeam = Auth::user()->currentTeam->reference_id;

        return redirect()->route("team.posts.index", [
            "teamId" => $team->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($teamId, Post $post, $slug)
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

        // Decode the slug parameter
        $slug = urldecode($slug);

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

        $authors = null;

        if ($post->show_author) {
            $authors = $post->authors;
        }

        $categories = $post->categories;

        // Render the post
        return Inertia::render($postRenderView, [
            "post" => $post,
            "authors" => $authors,
            "categories" => $categories,
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

        $authors = null;

        if ($post->authors !== null) {
            // Fetch related authors
            $relatedAuthors = $post->authors;

            // Loop through related authors and create an array of authors with the required information
            foreach ($relatedAuthors as $user) {
                $authors[] = [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "username" => $user->username,
                    "email" => $user->email,
                    "profile_photo_path" => $user->profile_photo_path,
                    "profile_photo_url" => $user->profile_photo_url,
                ];
            }
        }

        $coverImages = $post->coverImages;
        $categories = $post->categories;

        return Inertia::render("Posts/UpdatePost/UpdatePost", [
            "post" => $post,
            "postAuthor" => $authors,
            "coverImages" => $coverImages,
            "categories" => $categories,
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

        // Initialize the $authorId variable to null
        $authorId = null;

        // Create the post and store it in a variable
        $post->update([
            "user_id" => $userId,
            "team_id" => $teamId,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        // Get the post ID
        $postId = $post->id;

        // Update authors
        if (
            $post->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Retrieve the existing author IDs for the post
            $existingAuthorIds = AuthorPost::where("post_id", $postId)
                ->pluck("user_id")
                ->toArray();

            // Loop through the authors array and update or create a record in the author_post table
            $updatedAuthorIds = [];
            foreach ($request->author as $author) {
                $authorId = $author["id"];
                $updatedAuthorIds[] = $authorId;

                // Check if a user record with this ID exists
                $userModel = User::find($authorId);

                if ($userModel) {
                    // Update or create the record in the AuthorPost table
                    AuthorPost::updateOrCreate([
                        "user_id" => $authorId,
                        "post_id" => $postId,
                    ]);
                }
            }

            // Delete the AuthorPost records that are not present in the request
            $authorsToDelete = array_diff(
                $existingAuthorIds,
                $updatedAuthorIds
            );
            AuthorPost::where("post_id", $postId)
                ->whereIn("user_id", $authorsToDelete)
                ->delete();
        }

        // Update cover images # start

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

        return redirect()->route("team.posts.index", [
            "teamId" => $team->id,
        ]);
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
