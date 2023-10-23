<?php

namespace App\Http\Controllers\Api\Internal\Guest\Features;

use App\Http\Controllers\Controller;
use App\Models\Store\Store;
use Illuminate\Http\Request;

class StoresGuestIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        sleep(1);
        $query = Store::latest()
            ->with("categories")
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("title", "LIKE", "%" . $term . "%");
            });

        $posts = $query->paginate(10);

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
