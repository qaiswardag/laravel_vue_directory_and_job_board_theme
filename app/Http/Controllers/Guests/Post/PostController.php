<?php

namespace App\Http\Controllers\Guests\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all posts
        $posts = Post::latest()
            // Search posts by title or content
            ->when($request->query("search_query"), function ($query, $term) {
                $query
                    ->where("title", "LIKE", "%" . $term . "%")
                    ->orWhere("content", "LIKE", "%" . $term . "%");
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    // Show all published posts for everyone
                    $query->where("published", 1)->orWhere(function ($query) {
                        $query
                            ->where(function ($query) {
                                // Show published posts for users belonging to the team
                                $query->where("published", 1);
                                if (
                                    Auth::user() &&
                                    Auth::user()->current_team_id
                                ) {
                                    // Show published posts for users belonging to the team with team ID
                                    $query->orWhere(
                                        "team_id",
                                        Auth::user()->current_team_id
                                    );
                                }
                            })
                            ->orWhere(function ($query) {
                                if (
                                    Auth::user() &&
                                    Auth::user()->current_team_id
                                ) {
                                    // Show private posts for users belonging to the team with team ID
                                    $query
                                        ->where(
                                            "team_id",
                                            Auth::user()->current_team_id
                                        )
                                        ->where("published", 0);
                                }
                            });
                    });
                });

                // Show published and private posts for superadmin
                if (Auth::user() && Auth::user()->superadmin === 1) {
                    $query->orWhere("published", 0);
                }
            })
            // Eager load related teams and users
            ->with(["team:id,name,thumbnail", "user:id,first_name,last_name"])
            ->paginate(10);

        // Hide certain attributes from the JSON response
        $posts->makeHidden(["id", "user_id", "team_id"]);

        // Transform the posts
        $posts->transform(function ($post) {
            if ($post->user) {
                $post->user->makeHidden(["id", "profile_photo_url"]);
            }

            if ($post->team) {
                $post->team->makeHidden(["id"]);
            }

            return $post;
        });

        // Render the view
        return Inertia::render("Guests/Posts/Index", [
            "posts" => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slugId The slug id.
     * @param  string  $slug The slug of the post.
     * @return \Inertia\Response A rendered view with post data or an error view if needed.
     */
    public function show($slugId, $slug)
    {
        // The view that will be rendered
        $postRenderView = "Guests/Posts/Show";

        // Decoding the slug parameter
        $slug = urldecode($slug);

        // Find the post with the given slugId and slug, including related teams and users
        $post = Post::where([["slug", "=", $slug], ["slug_id", "=", $slugId]])
            ->with([
                "team:id,name,thumbnail", // Include team data in the post object.
                "user:id,first_name,last_name", // Include user data in the post object.
            ])
            ->firstOrFail();

        // If the post is unpublished and the user is not authenticated, return a 404 error
        if ($post->published === 0 && !Auth::check()) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Hide certain attributes from the JSON response
        $post->makeHidden(["id", "user_id", "team_id"]);

        // Make the user id hidden from the JSON response
        if ($post->user) {
            $post->user->makeHidden(["id", "profile_photo_url"]);
        }

        // Make the team id hidden from the JSON response
        if ($post->team) {
            $post->team->makeHidden(["id"]);
        }

        // If the post is unpublished and the user is a superadmin, render the post anyway
        if ($post->published === 0 && Auth::user()->superadmin === 1) {
            return Inertia::render($postRenderView, [
                "post" => $post,
            ]);
        }

        // If the post is unpublished and the user does not belong to the team that owns the post, return a 404 error
        if (
            $post->published === 0 &&
            $post->team_id !== Auth::user()->current_team_id
        ) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Render the post
        return Inertia::render($postRenderView, [
            "post" => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
