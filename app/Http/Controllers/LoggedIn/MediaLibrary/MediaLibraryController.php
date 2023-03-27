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
use Illuminate\Support\Facades\File;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team)
    {
        $this->authorize("can-read", $team);

        $images = MediaLibrary::latest()
            ->where("team_id", $team->id)
            ->take(16)
            ->get();

        return Inertia::render("Media/Media", [
            "images" => $images,
        ]);
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

            // original client file name
            $originalClientFileName = $image->getClientOriginalName();

            // extract the filename without the extension using pathinfo()
            $filenameWithoutExtension = pathinfo(
                $originalClientFileName,
                PATHINFO_FILENAME
            );

            // slugify the filename without the extension
            $slugifyFilename = Str::slug($filenameWithoutExtension, "_");

            // get the current timestamp
            $timestamp = time();

            // generate a unique ID for the image
            $randomString = Str::random(rand(8, 14)) . strval($timestamp);
            // convert the random string to lowercase using strtolower()
            $randomString = strtolower($randomString);

            // get the current year and month in YYYY/MM format
            $currentYearYear = date("Y"); // get the current year and month in YYYY/MM format
            $currentMonth = date("m"); // get the current year and month in YYYY/MM format

            // extension
            $extension = $image->extension();

            $path =
                $teamReferenceId .
                "/" .
                $currentYearYear .
                "/" .
                $currentMonth .
                "/" .
                $slugifyFilename .
                "_" .
                $randomString .
                "." .
                $extension;

            // check if the path already exists in the media_libraries table
            if (MediaLibrary::where("path", $path)->exists()) {
                // if the path already exists, change the path
                $path =
                    $teamReferenceId .
                    "/" .
                    $currentYearYear .
                    "/" .
                    $currentMonth .
                    "/" .
                    $slugifyFilename .
                    "_" .
                    $randomString .
                    "." .
                    $extension;
            }

            $image->storeAs($path);

            // file size
            $fileSizeKb = intval($image->getSize() / 1024);

            $width = null;
            $height = null;
            // check if file exists
            if (File::exists("uploads/" . $path) === true) {
                // The exif_imagetype function is a built-in PHP function that returns the image
                // type of a file. In this case, the function is used to check if the file located
                // at the path "uploads/" . $path is an image file. If the function returns a value
                // that is not false, it means that the file is an image file, and the condition
                // will evaluate to true.
                $imageType = exif_imagetype(public_path("uploads/" . $path));
                // If `imageType` is not false it means that the file is an image file
                if ($imageType !== false) {
                    list($width, $height) = getimagesize(
                        public_path("uploads/" . $path)
                    );
                }
            }

            $width = intval($width);
            $height = intval($height);

            // Image eloquent
            MediaLibrary::create([
                "user_id" => $request->user_id,
                "team_id" => $team->id,
                "name" => null,
                "path" => $path,
                "size" => $fileSizeKb,
                "width" => $width,
                "height" => $height,
            ]);
        }

        return redirect()
            ->back()
            ->with("success", "Successfully uploaded images.");
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaLibrary $mediaLibrary, Team $team)
    {
        // $this->authorize("can-read", $team);
        // if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        //     return Inertia::render("Error", [
        //         "customError" =>
        //             "The URL can only consist of letters, numbers, and underscores. Special characters are not allowed.",
        //         "status" => 404,
        //     ]);
        // }

        // dd();
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
    public function update(Request $request, Team $team)
    {
        $this->authorize("can-create-and-update", $team);

        $request->validate([
            "name" => ["required", "min:1", "max:255"],
        ]);

        $image = MediaLibrary::findOrFail($request->image_id);
        $image->name = $request->name;
        $image->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Team $team)
    {
        $this->authorize("can-destroy", $team);
        $image = MediaLibrary::findOrFail($request->image_id);

        $imagePath = public_path("uploads/" . $image->path);

        if (File::exists($imagePath) === false) {
            // delete the image record from the dat
            $image->delete();

            return back()->with(
                "error",
                "The file you requested to delete does not exist on the server. Record has been deleted from database."
            );
        }

        // delete the file from the public directory
        if (File::exists($imagePath) === true) {
            File::delete($imagePath);
        }

        //
        // delete the image record from the dat
        $image->delete();

        return back()->with(
            "success",
            "The image has been successfully deleted."
        );
    }
}
