<?php

namespace App\Http\Controllers\Guests\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::latest()
            ->when($request->query("search_query"), function ($query, $term) {
                $query
                    ->where("title", "LIKE", "%" . $term . "%")
                    ->orWhere("content", "LIKE", "%" . $term . "%");
            })
            ->where(function ($query) {
                // Show all published posts for everyone
                $query->where(function ($query) {
                    $query->where("published", 1)->orWhere(function ($query) {
                        // Show published posts for users belonging to the team

                        $query->where(function ($query) {
                            $query
                                ->where(function ($query) {
                                    $query->where("published", 1);
                                    if (
                                        Auth::user() &&
                                        Auth::user()->current_team_id
                                    ) {
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
                });
                // Show published and private posts for superadmin
                if (Auth::user() && Auth::user()->superadmin === 1) {
                    $query->orWhere("published", 0);
                }
            })
            ->with(["team:id,name,thumbnail", "user:id,first_name,last_name"])
            ->paginate(10);

        $posts->makeHidden(["id", "user_id", "team_id"]);

        $posts->transform(function ($post) {
            if ($post->user) {
                $post->user->makeHidden(["id", "profile_photo_url"]);
            }

            if ($post->team) {
                $post->team->makeHidden(["id"]);
            }

            return $post;
        });

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $postShow = "Guests/Posts/Show";
        $slug = urldecode($slug);

        $post = Post::where("slug", $slug)
            ->with(["team:id,name,thumbnail", "user:id,first_name,last_name"])
            ->firstOrFail();

        if ($post->published === 0 && !Auth::check()) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.",
                "status" => 404,
            ]);
        }

        $post->makeHidden(["id", "user_id", "team_id"]);

        // Make the user id hidden
        if ($post->user) {
            $post->user->makeHidden(["id", "profile_photo_url"]);
        }

        if ($post->team) {
            $post->team->makeHidden(["id"]);
        }

        if ($post->published === 0 && Auth::user()->superadmin === 1) {
            return Inertia::render($postShow, [
                "post" => $post,
            ]);
        }
        if (
            $post->published === 0 &&
            $post->team_id !== Auth::user()->current_team_id
        ) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.",
                "status" => 404,
            ]);
        }

        if ($post->published === 0 && Auth::user()->superadmin === 1) {
            return Inertia::render($postShow, [
                "post" => $post,
            ]);
        }

        return Inertia::render($postShow, [
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
     * @param  \Illuminate\Http\Request  $request
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
