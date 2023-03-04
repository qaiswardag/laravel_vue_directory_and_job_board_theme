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

        $images = $request->input("images");

        foreach ($images as $image) {
            dd($image);
            $img_name = Str::uuid()->toString(); // generate a unique ID for the image
            $data = base64_decode(
                preg_replace("#^data:image/\w+;base64,#i", "", $image["data"])
            );

            $path = $team->id . "-" . $img_name . "." . $image["extension"];

            Storage::disk("public")->put(
                "images/temp/" . $team->id . "/" . $path,
                $data
            );

            // move the image to the correct folder with a unique ID in the name
            Storage::disk("public")->move(
                "images/temp/" . $team->id . "/" . $path,

                "images/" . $team->id . "/" . $path
            );

            // open the image using Intervention Image
            $interventionImage = Image::make(
                storage_path("app/public/images/" . $team->id . "/" . $path)
            );

            $sizeKB = $interventionImage->filesize() / 1024; // divide by 1024 to convert bytes to KB

            // get the image's dimensions
            $width = $interventionImage->width();
            $height = $interventionImage->height();

            // Image eloquent
            MediaLibrary::create([
                "user_id" => $request->user_id,
                "team_id" => $team->id,
                "name" => null,
                "path" => $path,
                "size" => $sizeKB,
                "width" => $width,
                "height" => $height,
            ]);
        }

        return redirect()->back();
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
