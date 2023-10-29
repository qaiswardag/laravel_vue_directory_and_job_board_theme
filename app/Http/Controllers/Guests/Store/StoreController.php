<?php

namespace App\Http\Controllers\Guests\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Render the view
        return Inertia::render("Guests/Stores/Index", [
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
        $post = Store::where("id", $postId)
            ->where("published", true)
            ->firstOrFail();

        $postRenderView = "Guests/Items/SingleItem";

        $authors = $post->authors;
        $categories = $post->categories;
        $states = $post->states;
        $coverImages = $post->coverImages;

        // Render
        return Inertia::render($postRenderView, [
            "postType" => "Store",
            "post" => $post,
            "authors" => $authors,
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
