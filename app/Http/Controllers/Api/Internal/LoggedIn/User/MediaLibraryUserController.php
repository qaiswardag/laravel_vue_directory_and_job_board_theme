<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MediaLibraryUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MediaLibraryUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $searchQuery = "";
        $currentClickedPage = "";

        $totalResults = MediaLibraryUser::when(
            $request->query("search_query"),
            function ($query, $term) {
                $query->where("name", "LIKE", "%" . $term . "%");
            }
        )->count();

        $media = MediaLibraryUser::latest()
            ->where("user_id", $user->id)
            ->when($request->query("search_query"), function ($query, $term) {
                $query->where("name", "LIKE", "%" . $term . "%");
            })
            ->paginate(14);

        // check for search_query
        if ($request->search_query !== null) {
            $searchQuery = $request->search_query;
        }
        // check for current page
        if ($request->page !== null) {
            $currentClickedPage = $request->page;
        }

        // return
        return [
            "media" => $media,
            "search_query" => $searchQuery,
            "current_clicked_page" => $currentClickedPage,
            "total_results" => $totalResults,
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
    public function edit(MediaLibraryUser $mediaLibraryUser)
    {
        $uploadedBy = User::find($mediaLibraryUser->user_id);

        if ($uploadedBy !== null) {
            $uploadedBy = [
                "firstName" => $uploadedBy->first_name,
                "lastName" => $uploadedBy->last_name,
            ];
        }

        if ($uploadedBy === null) {
            $uploadedBy = [
                "firstName" => "Unknown",
                "lastName" => "Unknown",
            ];
        }

        // return
        return [
            "mediaLibrary" => $mediaLibraryUser,
            "uploadedBy" => $uploadedBy,
        ];
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
