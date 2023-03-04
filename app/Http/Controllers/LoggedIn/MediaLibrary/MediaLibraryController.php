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
            dd("see", $image->getClientOriginalName());
            $fileName = $image->getClientOriginalName();
            $fileSize = $image->getSize();

            $img_name = Str::uuid()->toString(); // generate a unique ID for the image

            // process the upload and save the file in storage
            // returns false if image does not save
            // $path = $file->storeAs('tmp/' . $folder, $fileName);
            $path = $image->storeAs(
                "qais",
                $img_name . "." . $image->extension()
            );

            // Image eloquent
            MediaLibrary::create([
                "user_id" => $request->user_id,
                "team_id" => $team->id,
                "name" => null,
                "path" => $path,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
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
