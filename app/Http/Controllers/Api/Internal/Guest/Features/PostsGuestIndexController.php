<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PostsGuestIndexController extends Controller
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

        $categories = PostCategory::orderBy("name")->get();

        $query = Post::latest()
            ->with("team")
            ->with("categories")
            ->with("stores")
            ->with("coverImages")
            ->where("published", true)
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            })
            ->where(function ($query) {
                $query
                    ->whereNotNull("started_at")
                    ->whereNotNull("ended_at")
                    ->whereNotNull("days_before_campaign_visibility");
            })
            ->whereRaw(
                "started_at <= NOW() + INTERVAL days_before_campaign_visibility DAY"
            );

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
                $query->whereIn("post_categories.id", $categoryIDs);
            });
        }

        $posts = $query->paginate(20);

        $posts->appends($request->all());

        return [
            "categories" => $categories,
            "posts" => $posts,
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
