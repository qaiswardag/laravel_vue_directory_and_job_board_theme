<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn;

use App\Http\Controllers\Controller;
use App\Models\Post\PostCategory;
use App\Models\Team;
use Illuminate\Http\Request;

class AttachPostCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Team $team)
    {
        $this->authorize("can-read", $team);

        $searchQuery = $request->input("search_query");

        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $items = PostCategory::query()
            ->when($request->query("search_query"), function (
                $query,
                $searchQuery
            ) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where("name", "LIKE", "%" . $searchQuery . "%");
                });
            })

            ->orderBy("name")
            ->paginate(10);

        //
        $count = $items->total();

        //

        return [
            "items" => $items,
            "count" => $count,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ];
    }
}
