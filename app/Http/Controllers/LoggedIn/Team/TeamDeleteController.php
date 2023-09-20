<?php

namespace App\Http\Controllers\LoggedIn\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Illuminate\Support\Facades\File;
use Laravel\Fortify\Actions\ConfirmPassword;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TeamDeleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($teamId)
    {
        $team = Team::where("id", $teamId)->first();

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => "Please try another route.", // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        Gate::authorize("view", $team);

        return Inertia::render("Teams/TeamDelete/TeamDelete", [
            "team" => $team->load("owner"),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
    public function destroy(Request $request, $teamId, StatefulGuard $guard)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        $this->authorize("can-destroy", $team);

        // Password confirm
        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->password
        );

        if (!$confirmed) {
            throw ValidationException::withMessages([
                "password" => __("The password is incorrect."),
            ]);
        }

        app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        $deleter = app(DeletesTeams::class);

        $teamImagesFolder = File::exists(
            storage_path(self::PUBLIC_UPLOAD_PATH . $teamId)
        );

        $path = storage_path(self::PUBLIC_UPLOAD_PATH . $teamId);

        // Check if the directory exists in storage
        if ($teamImagesFolder) {
            // Delete the directory from storage
            File::deleteDirectory($path);
        } else {
            Log::error(
                "An error occurred while deleting the images folder for team with ID: {$teamId}."
            );
        }

        // delete team
        $deleter->delete($team);

        return redirect()
            ->route("dashboard")
            ->with("success", "Successfully deleted the Team.");
    }
}
