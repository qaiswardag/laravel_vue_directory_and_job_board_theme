<?php

namespace App\Http\Controllers\LoggedIn\Job;

use App\Http\Controllers\Controller;
use App\Models\Job\AuthorJob;
use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use App\Models\Job\JobCategoryRelation;
use App\Models\Job\JobCountryRelation;
use App\Models\Job\JobCoverImageRelation;
use App\Models\Job\JobStateRelation;
use App\Models\Job\JobTypeRelation;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use Inertia\Inertia;


class JobExpiredController extends Controller
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
                $query
                    ->where('is_paid', true)
                    ->whereNotNull("ended_at");
                $query->where("ended_at", "<", Carbon::now());
            })
            ->orderBy('updated_at', 'desc')
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

        return Inertia::render("Jobs/IndexExpired", [
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
    public function republish($teamId, Job $job)
    {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected to store the job for, rather than the team that the user is currently on.
        $this->authorize("can-create-and-update", $team);


        $newJob = null;


        try {
            DB::transaction(function () use ($job, &$newJob) {
                // replicate new job # start
                $newJob = $job->replicate();
                $newJob->save();
                $newJob->update([
                    "published" => true,
                    "is_paid" => null,
                    "paid_at" => null,
                    "started_at" => null,
                    "ended_at" => null,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]);
                // replicate new job # end



                // replicate new categories # start
                if ($job->categories !== null) {
                    foreach ($job->categories as $category) {
                        // Create a new instance of the pivot model
                        $newCategoriesPivotData = new JobCategoryRelation([
                            'job_id' => $newJob->id,
                            'category_id' => $category->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newCategoriesPivotData->save();
                    }
                }
                // replicate new categories # end

                // replicate new cover images # start
                if ($job->coverImages !== null) {
                    foreach ($job->coverImages as $coverImage) {
                        // Create a new instance of the pivot model
                        $newCoverImagePivotData = new JobCoverImageRelation([
                            'job_id' => $newJob->id,
                            'media_library_id' => $coverImage->id,
                            "primary" => $coverImage->pivot->primary ?? null,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newCoverImagePivotData->save();
                    }
                }
                // replicate new cover images # start

                // replicate new cover images # start
                if ($job->authors !== null) {
                    foreach ($job->authors as $author) {
                        // Create a new instance of the pivot model
                        $newJobAuthorsPivotData = new AuthorJob([
                            'job_id' => $newJob->id,
                            'user_id' => $author->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobAuthorsPivotData->save();
                    }
                }
                // replicate new cover images # end

                // replicate new types # start
                if ($job->types !== null) {
                    foreach ($job->types as $type) {
                        // Create a new instance of the pivot model
                        $newJobTypesPivotData = new JobTypeRelation([
                            'job_id' => $newJob->id,
                            'type_id' => $type->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobTypesPivotData->save();
                    }
                }
                // replicate new types # end

                // replicate new states # start
                if ($job->states !== null) {
                    foreach ($job->states as $state) {
                        // Create a new instance of the pivot model
                        $newJobStatePivotData = new JobStateRelation([
                            'job_id' => $newJob->id,
                            'state_id' => $state->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobStatePivotData->save();
                    }
                }
                // replicate new states # end

                // replicate new countries # start
                if ($job->countries !== null) {
                    foreach ($job->countries as $country) {
                        // Create a new instance of the pivot model
                        $newJobStatePivotData = new JobCountryRelation([
                            'job_id' => $newJob->id,
                            'country_id' => $country->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobStatePivotData->save();
                    }
                }
                // replicate new countries # end

            });
        } catch (Exception $e) {
            Log::error(
                "Oops! Something went wrong. {$e->getMessage()}."
            );

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG, // Error message for the user.
                "status" => 422, // HTTP status code for the response.
            ]);
        }

        if ($newJob !== null) {
            return redirect()->route("team.jobs.job.edit", [
                "teamId" => $team->id,
                "job" => $newJob->id
            ]);
        }

        return Inertia::render("Error", [
            "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG,
            "status" => 422,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
