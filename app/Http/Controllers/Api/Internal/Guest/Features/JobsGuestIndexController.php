<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Job\Job;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobsGuestIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Job::latest()
            ->with("team")
            ->with("categories")
            ->with("coverImages")
            ->with("countries")
            ->with("states")
            ->with("authors")
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            });

        $posts = $query->paginate(20);

        return [
            "posts" => $posts,
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
