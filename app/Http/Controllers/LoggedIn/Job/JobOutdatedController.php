<?php

namespace App\Http\Controllers\LoggedIn\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\Job\StoreJobRequest;
use App\Models\Job\AuthorJob;
use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use App\Models\Job\JobCategoryRelation;
use App\Models\Job\JobCountry;
use App\Models\Job\JobCountryRelation;
use App\Models\Job\JobCoverImageRelation;
use App\Models\Job\JobState;
use App\Models\Job\JobStateRelation;
use App\Models\Job\JobType;
use App\Models\Job\JobTypeRelation;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\Job\JobStateFactory;
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

class JobOutdatedController extends Controller
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

        // Authorize the team that the user has selected to store the job for, rather than the team that the user is currently on.
        $this->authorize("can-read", $team);

        $jobs = $team
            ->jobs()
            ->with("coverImages")
            ->with("countries")
            ->with("states")
            ->with("categories")
            ->with("types")
            ->with("authors")
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            // Add the condition for started_at here
            ->where(function ($query) {
                // Include posts where ended_at is not null
                $query->whereNotNull("ended_at");
                // Include posts where ended_at is greater than the current date
                $query->where("ended_at", "<", Carbon::now());
            })
            ->latest()
            ->paginate(12);

        $jobs->appends($request->all());

        // Job created by
        // Retrieve user information for each job
        foreach ($jobs as $job) {
            $user = User::find($job->user_id);
            if ($user !== null) {
                $job->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "job_title" => $user->job_title,
                    "profile_photo_path" => $user->profile_photo_path,
                ];
            }
            if ($user === null) {
                $job->updatedBy = null;
            }
        }

        return Inertia::render("Jobs/IndexOutdated", [
            "posts" => $jobs,
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
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
