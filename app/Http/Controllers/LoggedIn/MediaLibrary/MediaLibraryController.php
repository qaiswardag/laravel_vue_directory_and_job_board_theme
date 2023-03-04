<?php

namespace App\Http\Controllers\LoggedIn\MediaLibrary;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\MediaLibrary\StoreMediaLibraryRequest;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team)
    {
        $this->authorize("can-read", $team);
        return Inertia::render("Media/Media");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team)
    {
        $this->authorize("can-create-and-update", $team);
    }

    /**
     * Store a newly created resource in storage.
     */

    // ...

    public function store(StoreMediaLibraryRequest $request)
    {
        // use and find the Team from request as that is the Team user want to store a Post for
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        foreach ($request->images as $image) {
            $teamReferenceId = $team->reference_id;
            $image = $image["file"];
            $timestamp = now()->timestamp;

            // original client file name
            $originalClientFileName = $image->getClientOriginalName();

            // extract the filename without the extension using pathinfo()
            $filenameWithoutExtension = pathinfo(
                $originalClientFileName,
                PATHINFO_FILENAME
            );

            // slugify the filename without the extension
            $slugifiedFilename = Str::slug($filenameWithoutExtension, "-");

            $fileSize = $image->getSize();

            // get the current timestamp
            $timestamp = time();
            // generate a unique ID for the image
            $randomString = Str::random(rand(8, 12)) . strval($timestamp);
            // convert the random string to lowercase using strtolower()
            $randomString = strtolower($randomString);

            // get the current year and month in YYYY/MM format
            $currentYearMonth = date("Y/m"); // get the current year and month in YYYY/MM format
            // replace the forward slash with a dash using str_replace()
            $currentYearMonth = str_replace("/", "-", $currentYearMonth);

            $path =
                $teamReferenceId .
                "/" .
                $slugifiedFilename .
                "-" .
                $currentYearMonth .
                "-" .
                $randomString .
                "." .
                $image->extension();

            // check if the path already exists in the media_libraries table
            if (MediaLibrary::where("path", $path)->exists()) {
                // If the path already exists, change the path
                $path =
                    $teamReferenceId .
                    "/" .
                    $slugifiedFilename .
                    "-" .
                    $currentYearMonth .
                    "-" .
                    $randomString .
                    "." .
                    $image->extension();
            }

            $filePath = $image->storeAs($path);

            // Image eloquent
            MediaLibrary::create([
                "user_id" => $request->user_id,
                "team_id" => $team->id,
                "name" => null,
                "path" => $filePath,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
            ]);
        }

        return redirect()
            ->back()
            ->with("success", "Successfully uploaded images.");
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-read", $team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-create-and-update", $team);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-create-and-update", $team);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaLibrary $mediaLibrary)
    {
        // $this->authorize("can-destroy", $team);
    }
}
