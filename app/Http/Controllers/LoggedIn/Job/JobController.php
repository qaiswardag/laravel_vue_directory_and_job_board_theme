<?php

namespace App\Http\Controllers\LoggedIn\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Job\StoreJobRequest;
use App\Models\Job\AuthorJob;
use App\Models\Job\Job;
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

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $referenceId)
    {
        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        // Authorize the team that the user has selected to store the job for, rather than the team that the user is currently on.
        $this->authorize("can-read", $team);

        $jobs = $team
            ->jobs()
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->latest()
            ->paginate(5);

        $jobs->appends($request->all());

        // Job created by
        // Retrieve user information for each job
        foreach ($jobs as $job) {
            $user = User::find($job->user_id);
            if ($user !== null) {
                $job->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "profile_photo_path" => $user->profile_photo_path,
                ];
            }
            if ($user === null) {
                $job->updatedBy = null;
            }
        }

        return Inertia::render("Jobs/Index", [
            "posts" => $jobs,
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
    public function create($referenceId)
    {
        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-create-and-update", $team);
        return Inertia::render("Jobs/CreatePost/CreatePost");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        // Find the current team that the user is on, rather than the team that the user is storing the job for.
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        $title = $request->title;
        $content = $request->content;
        $userId = $request->user_id;

        // slug
        $slug = Str::lower(Str::slug($request->slug, "_"));

        // Create the job and store it in a variable
        $job = Job::create([
            "user_id" => $userId,
            "team_id" => $team->id,
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

        // Check if the "show_author" property of the $request object is true
        // and the "author" property of the $request object is not null
        if (
            $request->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Get the job ID
            $jobId = $job->id;

            // Loop through the authors array and attach each author to the job
            foreach ($request->author as $author) {
                $authorId = $author["id"];

                // Create a new record in the author_job table
                AuthorJob::create([
                    "user_id" => $authorId,
                    "job_id" => $jobId,
                ]);
            }
        }

        // Return the current team that the user is on, rather than the team that the user is storing the job for.
        $currentTeam = Auth::user()->currentTeam->reference_id;

        return redirect()->route("team.jobs.index", $currentTeam);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job\Job $job
     * @return \Illuminate\Http\Response
     */
    public function show($referenceId, Job $job, $slug)
    {
        $jobRenderView = "Teams/Jobs/Show/ShowTeamJob";

        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        // Decode the slug parameter
        $slug = urldecode($slug);

        // Retrieve the user associated with the job
        $user = User::find($job->user_id);

        // Update the $job array with updatedBy information
        if ($user !== null) {
            $job->updatedBy = [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "profile_photo_path" => $user->profile_photo_path,
            ];
        }
        if ($user === null) {
            $job->updatedBy = null;
        }

        // Retrieve the authors associated with the job
        $authors = AuthorJob::where("job_id", $job->id)->get();

        // Update the $job array with updatedBy information
        if ($authors !== null) {
            $job->authors = $authors->map(function ($author) {
                return [
                    "first_name" => $author->user->first_name,
                    "last_name" => $author->user->last_name,
                    "profile_photo_path" => $author->user->profile_photo_path,
                ];
            });
        }

        if ($authors === null) {
            $job->authors = null;
        }

        // Render the job
        return Inertia::render($jobRenderView, [
            "post" => $job,
            "authors" => $authors,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit($referenceId, Job $job)
    {
        $team = Team::where("reference_id", $referenceId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected to store the job for, rather than the team that the user is currently on.
        $this->authorize("can-create-and-update", $team);

        $authors = null;

        if ($job->show_author === 1 && $job->authors !== null) {
            // Fetch related authors
            $relatedAuthors = $job->authors;

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

        return Inertia::render("Jobs/UpdatePost/UpdatePost", [
            "post" => $job,
            "postAuthor" => $authors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(StoreJobRequest $request, Job $job)
    {
        // Find the current team that the user is on, rather than the team that the user is storing the job for.
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        $slug = $request->slug;

        $title = $request->title;
        $content = $request->content;
        $teamId = $request->team["id"];
        $userId = $request->user_id;

        // Initialize the $authorId variable to null
        $authorId = null;

        // Create the job and store it in a variable
        $job->update([
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
            $job->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Get the job ID
            $jobId = $job->id;

            // Retrieve the existing author IDs for the job
            $existingAuthorIds = AuthorJob::where("job_id", $jobId)
                ->pluck("user_id")
                ->toArray();

            // Loop through the authors array and update or create a record in the author_jobs table
            $updatedAuthorIds = [];
            foreach ($request->author as $author) {
                $authorId = $author["id"];
                $updatedAuthorIds[] = $authorId;

                // Update or create the record in the AuthorJob table
                AuthorJob::updateOrCreate(
                    ["user_id" => $authorId, "job_id" => $jobId],
                    ["user_id" => $authorId, "job_id" => $jobId]
                );
            }

            // Delete the AuthorJob records that are not present in the request
            $authorsToDelete = array_diff(
                $existingAuthorIds,
                $updatedAuthorIds
            );
            AuthorJob::where("job_id", $jobId)
                ->whereIn("user_id", $authorsToDelete)
                ->delete();
        }
        return redirect()->route("team.jobs.index", [
            "referenceId" => $team->reference_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job, Team $team)
    {
        $this->authorize("can-destroy", $team);

        $job->delete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the Job with id: {$job->id}."
            );
    }
}
