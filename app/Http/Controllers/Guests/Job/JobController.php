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
            ->where("is_paid", true)
            ->where(function ($query) {
                $query
                    ->whereNotNull("started_at")
                    ->whereNotNull("ended_at")
                    ->where("started_at", "<", now())
                    ->where("ended_at", ">", now());
            })
            ->firstOrFail();


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
