<?php

namespace App\Http\Controllers\Guests\Job;

use App\Http\Controllers\Controller;
use App\Models\Job\Job;
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
        $post = Job::where("id", $postId)
            ->where("published", true)
            ->firstOrFail();

        // Check if the current time is earlier than the post's started_at timestamp
        if (Carbon::now()->lt($post->started_at)) {
            return Inertia::render("Error", [
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $postRenderView = "Guests/Items/SingleItem";

        $authors = $post->authors;
        $categories = $post->categories;
        $countries = $post->countries;
        $jobTypes = $post->types;
        $states = $post->states;
        $coverImages = $post->coverImages;

        // Render the store
        return Inertia::render($postRenderView, [
            "postType" => "Job",
            "post" => $post,
            "authors" => $authors,
            "countries" => $countries,
            "jobTypes" => $jobTypes,
            "states" => $states,
            "categories" => $categories,
            "coverImages" => $coverImages,
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
