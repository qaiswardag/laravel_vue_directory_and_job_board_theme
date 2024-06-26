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
use Exception;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
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

        $this->authorize("can-read", $team);

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
            ->where(function ($query) {
                $query->where("is_paid", true)->where(function ($query) {
                    $query
                        ->where("ended_at", ">", Carbon::now())
                        ->orWhereNull("ended_at");
                });
            })
            ->orderBy("updated_at", "desc")
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
                    "id" => $user->id,
                    "username" => $user->username,
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDraft(Request $request, $teamId)
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
            ->where(function ($query) {
                $query
                    ->where(function ($subquery) {
                        $subquery
                            ->where("is_paid", false)
                            ->where(function ($subsubquery) {
                                $subsubquery
                                    ->where("ended_at", ">", Carbon::now())
                                    ->orWhereNull("ended_at");
                            });
                    })
                    ->orWhereNull("is_paid");
            })
            ->orderBy("updated_at", "desc")
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
                    "id" => $user->id,
                    "username" => $user->username,
                ];
            }
            if ($user === null) {
                $job->updatedBy = null;
            }
        }

        return Inertia::render("Jobs/IndexDraft", [
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
    public function create($teamId)
    {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        return Inertia::render("Jobs/CreateJob/CreateJob");
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
        $startedAt = $request->started_at;
        $content = $request->content;
        $userId = $request->user_id;

        // slug
        $slug = Str::lower(Str::slug($request->slug, "_"));

        $endedAt = Carbon::parse($startedAt)->addDays(30);

        // Create the job and store it in a variable
        $job = Job::create([
            "user_id" => $userId,
            "team_id" => $team->id,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,
            "started_at" => $startedAt,
            "ended_at" => $endedAt,
            "is_filled" => $request->is_filled,
            "apply_via_link" => $request->apply_via_link,
            "apply_via_email" => $request->apply_via_email,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        // Get the job ID
        $jobId = $job->id;

        // authors
        // Check if the "show_author" property of the $request object is true
        // and the "author" property of the $request object is not null
        if (
            $request->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Loop through the authors array and attach each author to the job
            foreach ($request->author as $author) {
                $authorId = $author["id"];

                // Check if a user record with this ID exists
                $userModel = User::find($authorId);

                if ($userModel) {
                    // Create a new record in the author_job table
                    AuthorJob::create([
                        "user_id" => $authorId,
                        "job_id" => $jobId,
                    ]);
                }
            }
        }

        // cover images
        if (
            $request->cover_image !== null &&
            is_array($request->cover_image) &&
            count($request->cover_image) !== 0
        ) {
            // Loop through the array and attach each item to the job
            foreach ($request->cover_image as $image) {
                // Check if the "id" key exists in the $image array
                if (array_key_exists("id", $image)) {
                    $imageId = $image["id"];

                    // Check if a media library record with this ID exists
                    $mediaLibrary = MediaLibrary::find($imageId);
                    if ($mediaLibrary) {
                        // Determine the primary status from the pivot
                        $isPrimary = isset($image["pivot"]["primary"])
                            ? $image["pivot"]["primary"]
                            : false;

                        // Create a new record in the table
                        JobCoverImageRelation::create([
                            "media_library_id" => $imageId,
                            "job_id" => $jobId,
                            "primary" => $isPrimary,
                        ]);
                    }
                }
            }
        }

        // countries
        if (
            $request->countries !== null &&
            gettype($request->countries) === "array" &&
            count($request->countries) !== 0
        ) {
            // Loop through the countries array and attach each country to the post
            foreach ($request->countries as $country) {
                $countryId = $country["id"];

                // Check if a category record with this ID exists
                $countryModel = JobCountry::find($countryId);

                if ($countryModel) {
                    // Create a new record in the table
                    JobCountryRelation::create([
                        "country_id" => $countryId,
                        "job_id" => $jobId,
                    ]);
                }
            }
        }
        // states
        if (
            $request->states !== null &&
            gettype($request->states) === "array" &&
            count($request->states) !== 0
        ) {
            // Loop through the states array and attach each state to the post
            foreach ($request->states as $state) {
                $stateId = $state["id"];

                // Check if a state record with this ID exists
                $statesModel = JobState::find($stateId);

                if ($statesModel) {
                    // Create a new record in the table
                    JobStateRelation::create([
                        "state_id" => $stateId,
                        "job_id" => $jobId,
                    ]);
                }
            }
        }
        // categories
        if (
            $request->categories !== null &&
            gettype($request->categories) === "array" &&
            count($request->categories) !== 0
        ) {
            // Loop through the categories array and attach each category to the post
            foreach ($request->categories as $category) {
                $categoryId = $category["id"];

                // Check if a category record with this ID exists
                $categoryModel = JobCategory::find($categoryId);

                if ($categoryModel) {
                    // Create a new record in the author_post table
                    JobCategoryRelation::create([
                        "category_id" => $categoryId,
                        "job_id" => $jobId,
                    ]);
                }
            }
        }

        // types
        if (
            $request->types !== null &&
            gettype($request->types) === "array" &&
            count($request->types) !== 0
        ) {
            // Loop through the types array and attach each category to the post
            foreach ($request->types as $jobType) {
                $typeId = $jobType["id"];

                // Check if a type record with this ID exists
                $typesModel = JobType::find($typeId);

                if ($typesModel) {
                    // Create a new record in the author_post table
                    JobTypeRelation::create([
                        "type_id" => $typeId,
                        "job_id" => $jobId,
                    ]);
                }
            }
        }

        // job paid logic # start
        if ($request->published && Auth::user()->superadmin === null) {
            return redirect()->route("stripe.single.charge.job.create", [
                "team" => $team->id,
                "job" => $job->id,
            ]);
        }

        if ($request->published && Auth::user()->superadmin) {
            $job->update([
                "is_paid" => true,
                "paid_at" => Carbon::now(),
            ]);

            return redirect()->route("team.jobs.index", [
                "teamId" => $team->id,
            ]);
        }
        //
        // if published is false. User just created a draft
        if (!$request->published) {
            return redirect()->route("team.jobs.index.draft", [
                "teamId" => $team->id,
            ]);
        }
        // job paid logic # end
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job\Job $job
     * @return \Illuminate\Http\Response
     */
    public function show($teamId, $slug, $jobId)
    {
        $jobRenderView = "Jobs/Show/ShowTeamJob";

        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        // Retrieve the post, including soft-deleted posts
        $job = Job::withTrashed()
            ->with("coverImages")
            ->findOrFail($jobId);

        // Retrieve the user associated with the job
        $user = User::find($job->user_id);

        // Update the $job array with updatedBy information
        if ($user !== null) {
            $job->updatedBy = [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "profile_photo_path" => $user->profile_photo_path,
                "id" => $user->id,
                "username" => $user->username,
            ];
        }
        if ($user === null) {
            $job->updatedBy = null;
        }

        $authors = [];

        if ($job->show_author) {
            $authors = $job->authors()->get();
        }

        $categories = $job->categories;
        $countries = $job->countries;
        $states = $job->states;
        $jobTypes = $job->types;

        $jobTeam = Team::find($job->team_id);

        // Render the job
        return Inertia::render($jobRenderView, [
            "post" => $job,
            "authors" => $authors,
            "countries" => $countries,
            "states" => $states,
            "jobTypes" => $jobTypes,
            "categories" => $categories,
            "team" => $jobTeam,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit($teamId, Job $job)
    {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        $authors = null;

        if ($job->authors !== null) {
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
                ];
            }
        }

        $coverImages = $job->coverImages;
        $categories = $job->categories;
        $types = $job->types;
        $states = $job->states;
        $countries = $job->countries;

        return Inertia::render("Jobs/UpdateJob/UpdateJob", [
            "post" => $job,
            "postAuthor" => $authors,
            "coverImages" => $coverImages,
            "countries" => $countries,
            "states" => $states,
            "categories" => $categories,
            "types" => $types,
        ]);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request)
    {
        $team = Team::findOrFail($request->teamId);
        $job = Job::findOrFail($request->postId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected
        $this->authorize("can-create-and-update", $team);

        $newJob = null;

        try {
            DB::transaction(function () use ($job, &$newJob) {
                // replicate new job # start
                $newJob = $job->replicate();
                $newJob->save();
                $newJob->update([
                    "title" => $newJob->title . " " . "copy",
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
                            "job_id" => $newJob->id,
                            "category_id" => $category->id,
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
                            "job_id" => $newJob->id,
                            "media_library_id" => $coverImage->id,
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
                            "job_id" => $newJob->id,
                            "user_id" => $author->id,
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
                            "job_id" => $newJob->id,
                            "type_id" => $type->id,
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
                            "job_id" => $newJob->id,
                            "state_id" => $state->id,
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
                            "job_id" => $newJob->id,
                            "country_id" => $country->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobStatePivotData->save();
                    }
                }
                // replicate new countries # end
            });
        } catch (Exception $e) {
            Log::error("Oops! Something went wrong. {$e->getMessage()}.");

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG, // Error message for the user.
                "status" => 422, // HTTP status code for the response.
            ]);
        }

        if ($newJob !== null) {
            return redirect()->route("team.jobs.index.draft", [
                "teamId" => $team->id,
            ]);
        }

        return Inertia::render("Error", [
            "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG,
            "status" => 422,
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
        $startedAt = $request->started_at;
        $content = $request->content;
        $teamId = $request->team["id"];
        $userId = $request->user_id;

        // Initialize the $authorId variable to null
        $authorId = null;

        $endedAt = Carbon::parse($startedAt)->addDays(30);

        // Create the job and store it in a variable
        $job->update([
            "user_id" => $userId,
            "team_id" => $teamId,
            "started_at" => $startedAt,
            "ended_at" => $endedAt,
            "is_filled" => $request->is_filled,
            "apply_via_link" => $request->apply_via_link,
            "apply_via_email" => $request->apply_via_email,
            "title" => $title,
            "slug" => $slug,
            "published" => $request->published,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        // Get the job ID
        $jobId = $job->id;

        // Update authors
        if (
            $job->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Retrieve the existing author IDs for the job
            $existingAuthorIds = AuthorJob::where("job_id", $jobId)
                ->pluck("user_id")
                ->toArray();

            // Loop through the authors array and update or create a record in the author_jobs table
            $updatedAuthorIds = [];
            foreach ($request->author as $author) {
                $authorId = $author["id"];
                $updatedAuthorIds[] = $authorId;

                // Check if a user record with this ID exists
                $userModel = User::find($authorId);

                if ($userModel) {
                    // Update or create the record in the AuthorJob table
                    AuthorJob::updateOrCreate([
                        "user_id" => $authorId,
                        "job_id" => $jobId,
                    ]);
                }
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

        // Update cover images # start

        // Retrieve the existing cover image relationships for the post
        $existingCoverImages = JobCoverImageRelation::where(
            "job_id",
            $jobId
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingCoverImageIds = $existingCoverImages
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $request->cover_image is not null and is an array
        if (
            $request->cover_image !== null &&
            gettype($request->cover_image) === "array" &&
            count($request->cover_image) !== 0
        ) {
            foreach ($request->cover_image as $coverImage) {
                $imageId = $coverImage["id"];
                $isPrimary = isset($coverImage["pivot"]["primary"])
                    ? $coverImage["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                JobCoverImageRelation::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "job_id" => $jobId,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingCoverImageIds array
                $key = array_search($imageId, $existingCoverImageIds);
                if ($key !== false) {
                    unset($existingCoverImageIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        JobCoverImageRelation::where("job_id", $jobId)
            ->whereIn("media_library_id", $existingCoverImageIds)
            ->delete();

        // Update cover images # end

        // Update countries
        if (
            $request->countries !== null &&
            gettype($request->countries) === "array" &&
            count($request->countries) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = JobCountryRelation::where("job_id", $jobId)
                ->pluck("country_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->countries as $country) {
                $stateId = $country["id"];
                $updatedResourceIds[] = $stateId;

                // Update or create  record in the table
                JobCountryRelation::updateOrCreate([
                    "country_id" => $stateId,
                    "job_id" => $jobId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            JobCountryRelation::where("job_id", $jobId)
                ->whereIn("country_id", $resourcesToDelete)
                ->delete();
        }

        // Update states
        if (
            $request->states !== null &&
            gettype($request->states) === "array" &&
            count($request->states) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = JobStateRelation::where("job_id", $jobId)
                ->pluck("state_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->states as $state) {
                $stateId = $state["id"];
                $updatedResourceIds[] = $stateId;

                // Update or create  record in the table
                JobStateRelation::updateOrCreate([
                    "state_id" => $stateId,
                    "job_id" => $jobId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            JobStateRelation::where("job_id", $jobId)
                ->whereIn("state_id", $resourcesToDelete)
                ->delete();
        }

        // Update categories
        if (
            $request->categories !== null &&
            gettype($request->categories) === "array" &&
            count($request->categories) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = JobCategoryRelation::where("job_id", $jobId)
                ->pluck("category_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->categories as $category) {
                $categoryId = $category["id"];
                $updatedResourceIds[] = $categoryId;

                // Update or create  record in the table
                JobCategoryRelation::updateOrCreate([
                    "category_id" => $categoryId,
                    "job_id" => $jobId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            JobCategoryRelation::where("job_id", $jobId)
                ->whereIn("category_id", $resourcesToDelete)
                ->delete();
        }
        // Update types
        if (
            $request->types !== null &&
            gettype($request->types) === "array" &&
            count($request->types) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = JobTypeRelation::where("job_id", $jobId)
                ->pluck("type_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->types as $type) {
                $typeId = $type["id"];
                $updatedResourceIds[] = $typeId;

                // Update or create  record in the table
                JobTypeRelation::updateOrCreate([
                    "type_id" => $typeId,
                    "job_id" => $jobId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            JobTypeRelation::where("job_id", $jobId)
                ->whereIn("type_id", $resourcesToDelete)
                ->delete();
        }

        // job paid logic # start
        if (
            !$job->is_paid &&
            $request->published &&
            Auth::user()->superadmin === null
        ) {
            return redirect()->route("stripe.single.charge.job.create", [
                "team" => $team->id,
                "job" => $job->id,
            ]);
        }

        if (!$job->is_paid && $request->published && Auth::user()->superadmin) {
            $job->update([
                "is_paid" => true,
                "paid_at" => Carbon::now(),
            ]);

            return redirect()->route("team.jobs.index", [
                "teamId" => $team->id,
            ]);
        }

        // job unpaid logic # start
        if (!$job->is_paid && !$request->published) {
            return redirect()->route("team.jobs.index.draft", [
                "teamId" => $team->id,
            ]);
        }
        // job unpaid logic # end

        // if job have already been paid
        if ($job->is_paid) {
            return redirect()->route("team.jobs.index", [
                "teamId" => $team->id,
            ]);
        }
        // job paid logic # end
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
