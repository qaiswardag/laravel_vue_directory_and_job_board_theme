<?php

namespace App\Http\Controllers\Guests\Job;

use App\Http\Controllers\Controller;
use App\Models\Job\Job;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobController extends Controller
{
    public function index()
    {
        // Render the view
        return Inertia::render("Guests/Jobs/Index", [
            "posts" => null,
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
    public function show($teamSlug, $postSlug, $postId)
    {
        $job = Job::where("id", $postId)
            ->where("published", true)
            ->where("is_paid", true)
            ->with('coverImages')
            ->where(function ($query) {
                $query
                    ->whereNotNull("started_at")
                    ->whereNotNull("ended_at")
                    ->where("started_at", "<=", now()->addDays(1))
                    ->where("ended_at", ">=", now());
            })
            ->firstOrFail();


        $postRenderView = "Guests/Items/SingleItem";

        $authors = $job->authors;
        $categories = $job->categories;
        $countries = $job->countries;
        $jobTypes = $job->types;
        $states = $job->states;

        $jobTeam = Team::find($job->team_id);

        // Render the store
        return Inertia::render($postRenderView, [
            "postType" => "Job",
            "post" => $job,
            "authors" => $authors,
            "countries" => $countries,
            "jobTypes" => $jobTypes,
            "states" => $states,
            "categories" => $categories,
            "team" => $jobTeam,

        ]);
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
