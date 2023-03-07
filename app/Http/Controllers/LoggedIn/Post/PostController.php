<?php

namespace App\Http\Controllers\LoggedIn\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Post\StorePostRequest;
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

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Team $team)
    {
        $this->authorize("can-read", $team);

        $posts = $team
            ->posts()
            ->latest()
            ->paginate(2);

        return Inertia::render("Posts/Index", [
            "posts" => $posts,
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
        // use and find the Team from request as that is the Team user want to store a Post for
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        $title = $request->title;
        $content = $request->content;
        $userId = $request->user_id;

        Post::create([
            "user_id" => $userId,
            "team_id" => $team->id,
            "title" => $title,
            "slug" => $title,
            "published" => $request->published,
            "thumbnail" => $request->thumbnail,
            "content" => $content,
            "tags" => $request->tags,
        ]);

        // return current Team user is on and not the Team user is storing the post for
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
        dd("show post method logged in controller. post is:", $post);
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
        // $this->authorize("can-create-and-update", $team);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Team $team)
    {
        // $this->authorize("can-create-and-update", $team);
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
