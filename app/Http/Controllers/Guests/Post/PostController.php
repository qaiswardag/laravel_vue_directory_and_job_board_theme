<?php

namespace App\Http\Controllers\Guests\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function showAPI($teamSlug, $postSlug, $postId)
    {
        // Call the getShow method and store its return values
        list(
            $post,
            $authors,
            $categories,
            $states,
            $stores,
            $postTeam,
        ) = $this->getShow($postId);

        // Render
        return [
            "post" => $post,
            "postType" => "Campaign",
            "authors" => $authors,
            "states" => $states,
            "categories" => $categories,
            "stores" => $stores,
            "team" => $postTeam,
        ];
    }

    private function getShow($postId)
    {
        $postRenderView = "Guests/Items/SingleItem";

        $post = Post::findOrFail($postId);
        $startCampaignVisibility = $post->days_before_campaign_visibility;

        $post = Post::where("id", $postId)
            ->where("published", true)
            ->with("coverImages")
            ->where(function ($query) use ($startCampaignVisibility) {
                $query
                    ->whereNotNull("started_at")
                    ->whereNotNull("ended_at")
                    ->whereNotNull("days_before_campaign_visibility")
                    ->where(
                        "started_at",
                        "<=",
                        now()->addDays($startCampaignVisibility)
                    )
                    ->where("ended_at", ">=", now());
            })
            ->firstOrFail();

        $authors = $post->authors;
        $categories = $post->categories;
        $states = $post->states;

        $stores = $post
            ->stores()
            ->with("states")
            ->with("team")
            ->with("coverImages")
            ->with("brandLogos")
            ->get();

        $postTeam = Team::find($post->team_id);

        // Return the values as an array
        return [
            $post,
            $authors,
            $categories,
            $states,
            $stores,
            $postTeam,
            $postRenderView,
        ];
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
