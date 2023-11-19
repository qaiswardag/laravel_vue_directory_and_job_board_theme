<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use App\Models\Job\JobCountry;
use App\Models\Job\JobState;
use App\Models\Job\JobType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobsGuestIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $countries = JobCountry::orderBy("name")->get();
        $states = JobState::orderBy("name")->get();
        $types = JobType::orderBy("name")->get();
        $categories = JobCategory::orderBy("name")->get();

        $query = Job::latest()
            ->with("team")
            ->with("types")
            ->with("categories")
            ->with("coverImages")
            ->with("countries")
            ->with("states")
            ->with("authors")
            ->where("published", true)
            ->where("is_paid", true)
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            });

        // countries filter logic # start
        $countryIDs = [];

        if (isset($request->country) && is_array($request->country)) {
            foreach ($request->country as $country) {
                $countryIDs[] = $country["id"] ?? null;
            }
        }

        // Remove null values from the array
        $countryIDs = array_filter($countryIDs, function ($value) {
            return $value !== null;
        });

        if (!empty($countryIDs)) {
            $query->whereHas("countries", function ($query) use ($countryIDs) {
                $query->whereIn("job_countries.id", $countryIDs);
            });
        }
        // countries filter logic # end

        // states filter logic # start
        $stateIDs = [];

        if (isset($request->state) && is_array($request->state)) {
            foreach ($request->state as $state) {
                $stateIDs[] = $state["id"] ?? null;
            }
        }

        // Remove null values from the array
        $stateIDs = array_filter($stateIDs, function ($value) {
            return $value !== null;
        });

        if (!empty($stateIDs)) {
            $query->whereHas("states", function ($query) use ($stateIDs) {
                $query->whereIn("job_states.id", $stateIDs);
            });
        }
        // states filter logic # end

        // types filter logic # start
        $typeIDs = [];

        if (isset($request->type) && is_array($request->type)) {
            foreach ($request->type as $type) {
                $typeIDs[] = $type["id"] ?? null;
            }
        }

        // Remove null values from the array
        $typeIDs = array_filter($typeIDs, function ($value) {
            return $value !== null;
        });

        if (!empty($typeIDs)) {
            $query->whereHas("types", function ($query) use ($typeIDs) {
                $query->whereIn("job_types.id", $typeIDs);
            });
        }
        // types filter logic # end

        // categories filter logic # start
        $categoryIDs = [];

        if (isset($request->category) && is_array($request->category)) {
            foreach ($request->category as $category) {
                $categoryIDs[] = $category["id"] ?? null;
            }
        }

        // Remove null values from the array
        $categoryIDs = array_filter($categoryIDs, function ($value) {
            return $value !== null;
        });

        if (!empty($categoryIDs)) {
            $query->whereHas("categories", function ($query) use (
                $categoryIDs
            ) {
                $query->whereIn("job_categories.id", $categoryIDs);
            });
        }
        // categories filter logic # end


        // Add a condition to filter posts where ended_at is
        $query->where(function ($query) {
            // Include posts where ended_at is not null
            $query
                ->whereNotNull("started_at")
                ->whereNotNull("ended_at")
                ->where("started_at", "<", now())
                ->where("ended_at", ">", now());
        });


        $posts = $query->paginate(20);

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
