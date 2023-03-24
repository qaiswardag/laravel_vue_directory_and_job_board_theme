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
        // Authorize the current team that the user is on, rather than the team that the user is storing the post for.
        $this->authorize("can-read", $team);

        $posts = $team
            ->posts()
            ->latest()
            ->paginate(2);

        // post created by
        // $createdBy = User::findOrFail($post->user_id);

        return Inertia::render("Posts/Index", [
            "posts" => $posts,
            "createdBy" => $posts,
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

        dd("author is in controller store:", $request->author);

        Post::create([
            "user_id" => $userId,
            "team_id" => $team->id,
            "title" => $title,
            "slug" => $slug,
            "slug_id" => $slugId,
            "published" => $request->published,
            "thumbnail" => $request->thumbnail,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
            "author_id" => null,
        ]);

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
        // Authorize the current team that the user is on, rather than the team that the user is storing the post for.
        $this->authorize("can-create-and-update", $team);

        return Inertia::render("Posts/UpdatePost/UpdatePost", [
            "post" => $post,
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

        $post->update([
            "user_id" => $userId,
            "team_id" => $teamId,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,
            "thumbnail" => $request->thumbnail,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
            "author_id" => null,
        ]);

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
