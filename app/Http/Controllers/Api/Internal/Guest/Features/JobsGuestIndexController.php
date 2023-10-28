<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use App\Models\Job\JobCountry;
use App\Models\Job\JobState;
use App\Models\Job\JobType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobsGuestIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $categoryID = null;
        $typeID = null;
        $countryID = null;
        $stateID = null;

        if (isset($request->category["id"])) {
            $categoryID = $request->category["id"];
        }

        if (isset($request->type["id"])) {
            $typeID = $request->type["id"];
        }

        if (isset($request->country["id"])) {
            $countryID = $request->country["id"];
        }

        if (isset($request->state["id"])) {
            $stateID = $request->state["id"];
        }

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $countries = JobCountry::all();
        $states = JobState::all();
        $types = JobType::all();
        $categories = JobCategory::all();

        $query = Job::latest()
            ->with("team")
            ->with("types")
            ->with("categories")
            ->with("coverImages")
            ->with("countries")
            ->with("states")
            ->with("authors")
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            });

        if ($categoryID) {
            $query->whereHas("categories", function ($query) use ($categoryID) {
                $query->where("job_categories.id", $categoryID);
            });
        }

        if ($typeID) {
            $query->whereHas("types", function ($query) use ($typeID) {
                $query->where("job_types.id", $typeID);
            });
        }

        if ($countryID) {
            $query->whereHas("countries", function ($query) use ($countryID) {
                $query->where("job_countries.id", $countryID);
            });
        }

        if ($stateID) {
            $query->whereHas("states", function ($query) use ($stateID) {
                $query->where("job_states.id", $stateID);
            });
        }

        $posts = $query->paginate(2);

        $posts->appends($request->all());

        return [
            "types" => $types,
            "categories" => $categories,
            "posts" => $posts,
            "countries" => $countries,
            "states" => $states,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
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
