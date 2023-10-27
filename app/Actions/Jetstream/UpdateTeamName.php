<?php

namespace App\Actions\Jetstream;

use App\Http\Requests\LoggedIn\Team\StoreTeamUpdateRequest;
use App\Models\Team;
use App\Models\team\TeamCoverImageRelation;
use App\Models\team\TeamLogoRelation;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Illuminate\Support\Str;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  array<string, string>  $input
     * @var \Illuminate\Validation\Validator $validator
     */
    public function update(User $user, Team $team, array $input)
    {
        Gate::forUser($user)->authorize("update", $team);

        $slug = Str::lower(Str::slug($input["slug"], "_"));

        $coverImages = $input["cover_image"];
        $logos = $input["logo"];

        // Update cover images # start
        // Retrieve the existing cover image relationships for the post
        $existingCoverImages = TeamCoverImageRelation::where(
            "team_id",
            $team->id
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingCoverImageIds = $existingCoverImages
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $coverImages is not null and is an array
        if (
            $coverImages !== null &&
            is_array($coverImages) &&
            gettype($coverImages) === "array" &&
            count($coverImages) !== 0
        ) {
            foreach ($coverImages as $coverImage) {
                $imageId = $coverImage["id"];
                $isPrimary = isset($coverImage["pivot"]["primary"])
                    ? $coverImage["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                TeamCoverImageRelation::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "team_id" => $team->id,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingCoverImageIds array
                $key = array_search($imageId, $existingCoverImageIds);
                if ($key !== false) {
                    unset($existingCoverImageIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        TeamCoverImageRelation::where("team_id", $team->id)
            ->whereIn("media_library_id", $existingCoverImageIds)
            ->delete();

        // Update cover images # end
        //
        //
        //
        //
        //
        // Update logos # start
        // Retrieve the existing cover image relationships for the post
        $existingCoverLogos = TeamLogoRelation::where(
            "team_id",
            $team->id
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingCoverLogoIds = $existingCoverLogos
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $logos is not null and is an array
        if (
            $logos !== null &&
            is_array($logos) &&
            gettype($logos) === "array" &&
            count($logos) !== 0
        ) {
            foreach ($logos as $logo) {
                $logoId = $logo["id"];
                $isPrimary = isset($logo["pivot"]["primary"])
                    ? $logo["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                TeamLogoRelation::updateOrCreate(
                    [
                        "media_library_id" => $logoId,
                        "team_id" => $team->id,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingCoverLogoIds array
                $key = array_search($logoId, $existingCoverLogoIds);
                if ($key !== false) {
                    unset($existingCoverLogoIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        TeamLogoRelation::where("team_id", $team->id)
            ->whereIn("media_library_id", $existingCoverLogoIds)
            ->delete();

        // Update logos # end

        $team
            ->forceFill([
                "name" => $input["name"],
                "slug" => $slug,
                "public" => $input["public"],
            ])
            ->save();
    }
}
