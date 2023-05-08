<?php

namespace App\Http\Controllers\LoggedIn\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Post\StorePostRequest;
use App\Models\Post\AuthorPost;
use App\Models\Post\Post;
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
    public function index(Request $request, Team $team)
    {
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
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->latest()
            ->paginate(10);

        $posts->appends($request->all());

        // post created by
        // $createdBy = User::findOrFail($post->user_id);

        return Inertia::render("Posts/Index", [
            "posts" => $posts,
            "createdBy" => $posts,
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
    public function create(Team $team)
    {
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

        $slugId = 1000000; // the starting value of the slug_id

        do {
            $slugExists = Post::where([
                ["slug", "=", $slug],
                ["slug_id", "=", $slugId],
            ])->exists();

            if ($slugExists) {
                $slugId++; // increment the slug_id if the slug already exists
            }
        } while ($slugExists);

        // Create the post and store it in a variable
        $post = Post::create([
            "user_id" => $userId,
            "team_id" => $team->id,
            "title" => $title,
            "slug" => $slug,
            "slug_id" => $slugId,
            "published" => $request->published,
            "cover_image_original" => $request->cover_image_original,
            "cover_image_thumbnail" => $request->cover_image_thumbnail,
            "cover_image_medium" => $request->cover_image_medium,
            "cover_image_large" => $request->cover_image_large,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        // Check if the "show_author" property of the $request object is true
        // and the "author" property of the $request object is not null
        if (
            $request->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Get the post ID
            $postId = $post->id;

            // Loop through the authors array and attach each author to the post
            foreach ($request->author as $author) {
                $authorId = $author["id"];

                // Create a new record in the author_post table
                AuthorPost::create([
                    "user_id" => $authorId,
                    "post_id" => $postId,
                ]);
            }
        }

        // Return the current team that the user is on, rather than the team that the user is storing the post for.
        $currentTeam = Auth::user()->currentTeam;

        return redirect()->route("overview.posts.index", $currentTeam);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Team $team)
    {
        // $this->authorize("can-read", $team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post, Team $team)
    {
        // Authorize the team that the user has selected to store the post for, rather than the team that the user is currently on.
        $this->authorize("can-create-and-update", $team);

        $authors = null;

        if ($post->show_author === 1 && $post->authors !== null) {
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

        return Inertia::render("Posts/UpdatePost/UpdatePost", [
            "post" => $post,
            "postAuthor" => $authors,
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

        // dd("ok:", $request->author);

        // Create the post and store it in a variable
        $post->update([
            "user_id" => $userId,
            "team_id" => $teamId,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,

            "cover_image_original" => $request->cover_image_original,
            "cover_image_thumbnail" => $request->cover_image_thumbnail,
            "cover_image_medium" => $request->cover_image_medium,
            "cover_image_large" => $request->cover_image_large,

            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        if (
            $post->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Get the post ID
            $postId = $post->id;

            // Retrieve the existing author IDs for the post
            $existingAuthorIds = AuthorPost::where("post_id", $postId)
                ->pluck("user_id")
                ->toArray();

            // Loop through the authors array and update or create a record in the author_post table
            $updatedAuthorIds = [];
            foreach ($request->author as $author) {
                $authorId = $author["id"];
                $updatedAuthorIds[] = $authorId;

                // Update or create the record in the AuthorPost table
                AuthorPost::updateOrCreate(
                    ["user_id" => $authorId, "post_id" => $postId],
                    ["user_id" => $authorId, "post_id" => $postId]
                );
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

        //
        //
        //
        // Return the current team that the user is on, rather than the team that the user is storing the post for.
        $currentTeam = Auth::user()->currentTeam;

        return redirect()->route("overview.posts.index", $currentTeam);
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
