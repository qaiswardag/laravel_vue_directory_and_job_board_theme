<?php

namespace App\Http\Controllers\LoggedIn\Post;

use App\Http\Controllers\Controller;
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

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $team = Team::findOrFail(Auth()->user()->current_team_id);
        $posts = $team->posts()->paginate(2);

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
    public function store(Request $request, Post $post)
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
        dd("show post method in controller. post is:", $post);
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
    public function destroy(Post $post)
    {
        // dd(Auth::user());

        // Gate::forUser($user)->check("removeTeamMember", $team)
        // Gate::forUser(Auth::user())->check("removeGeneralResource", $post);

        $post->delete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the Post with id: {$post->id}."
            );
    }
}
