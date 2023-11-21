<?php

namespace App\Http\Controllers\LoggedIn\Post;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Post\StorePostRequest;
use App\Models\Job\JobCategory;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\AuthorPost;
use App\Models\Post\Post;
use App\Models\Post\PostCategoryRelation;
use App\Models\Post\PostCoverImageRelation;
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
use SoftDeletes;


class PostDeletedController extends Controller
{
    /**
     * Display a listing of the resource.
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
            ->onlyTrashed()
            ->with("coverImages")
            ->with("categories")
            ->with("authors")
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
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

        return Inertia::render("Posts/IndexTrash", [
            "posts" => $posts,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function restore($postId, Team $team)
    {
        $this->authorize("can-create-and-update", $team);


        $post = Post::withTrashed()->findOrFail($postId);

        $post->restore();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully restored."
            );
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId, Team $team)
    {
        $this->authorize("can-destroy", $team);

        $post = Post::withTrashed()->findOrFail($postId);

        $post->forceDelete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the Post."
            );
    }
}
