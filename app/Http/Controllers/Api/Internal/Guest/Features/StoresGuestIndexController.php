<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Store\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoresGuestIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        sleep(1);
        $currentClickedPage = "";

        $query = Store::latest()
            ->with("team")
            ->with("categories")
            ->with("coverImages")
            ->with("states")
            ->with("authors")
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            });

        $posts = $query->paginate(10);

        // check for search_query
        if ($request->search_query !== null) {
            $searchQuery = $request->search_query;
        }
        // check for current page
        if ($request->page !== null) {
            $currentClickedPage = $request->page;
        }

        return [
            "posts" => $posts,
            "current_clicked_page" => $currentClickedPage,
        ];
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
    public function show($teamSlug, $teamId, $postId, $postSlug)
    {
        $post = Store::findOrFail($postId);

        $postRenderView = "Guests/TemplateListResources/TemplateSingleResource";

        // Render the store
        return Inertia::render($postRenderView, [
            "post" => $post,
            "authors" => null,
            "states" => null,
            "categories" => null,
            "coverImages" => null,
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
