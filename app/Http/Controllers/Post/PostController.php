<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        /**
         * Get the Posts via Company
         *
         * $posts = Team::findOrFail(Auth()->user()->current_team_id);
         * $posts = $posts->posts()->paginate(2);
         *
         */
        //

        // user
        $posts = Team::findOrFail(5);
        $posts = $posts->posts()->paginate(2);

        //
        //
        //
        //
        // ->where("id", Auth()->user()->current_team_id)
        // Auth()->user()->current_team_id
        //
        //
        //

        //
        return Inertia::render("Posts/Index", [
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
        return Inertia::render("Posts/Partials/CreatePost");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $team)
    {
        sleep(1);
        dd($request);
        // ->foreignId("team_id")
        // dd($request);

        $title = $request->input("title");
        $content = $request->input("content");
        //
        //
        // Validator::make($request, [
        //     "title" => ["required", "string", "max:255"],
        //     "content" => ["required", "string", "max:255"],
        // ])->validateWithBag("createPost");
        // //
        //

        Post::create([
            "user_id" => 1,
            "team_id" => 1,
            // created by below user
            "title" => $title,
            "slug" => $title,
            "published" => 1,
            "image_cover_path" => "hi-i-am-path-for-image",
            "content" => $content,
        ]);

        return redirect()->route("overview.posts.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        sleep(1);

        if (Post::find($id) === null) {
            return redirect()
                ->route("overview.posts.index")
                ->with(
                    "error",
                    "Post not found. Post with following id not found: {$id}."
                );
        }

        Post::find($id)->delete();

        //
        return redirect()
            ->route("overview.posts.index")
            ->with("success", "Successfully deleted the Post with id: {$id}.");
    }
}
