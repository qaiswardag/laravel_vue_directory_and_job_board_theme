<?php

namespace App\Http\Controllers\LoggedIn\MediaLibrary;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\MediaLibrary\StoreMediaLibraryRequest;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

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
    public function store(StoreMediaLibraryRequest $request)
    {
        // use and find the Team from request as that is the Team user want to store a Post for
        $team = Team::findOrFail($request->team["id"]);
        $this->authorize("can-create-and-update", $team);

        // validate
        $request->validate([
            "images" => ["required"],
        ]);
        //
        //
        //
        // chat gpt
        // chat gpt
        // chat gpt
        // chat gpt
        $images = $request->input("images");

        foreach ($images as $image) {
            // image->store("image");
            // $data = base64_decode($image);
            // $filename = uniqid() . ".jpg"; // generate a unique filename
            // file_put_contents(public_path("images/" . $filename), $data);
            //
            dd("request er:", $image);
            //
            //
            //
            $img_name = "test-my-image-name";

            // process the upload and save the file in storage
            // returns false if image does not save
            // $path = $file->storeAs('tmp/' . $folder, $fileName);
            $path = $image->storeAs(
                "images/site-" . $siteId,
                $img_name . "." . $file->extension()
            );
        }

        // return back for testing - delete again
        return redirect()->back();
        //
        //
        //
        //
        //
        //
        //
        //
        // meeshop
        // meeshop
        // meeshop
        // meeshop
        // meeshop
        // meeshop
        // meeshop
        // set file and file name
        // if ($request->hasFile("image")) {
        //     $file = $request->file("image");
        //     $fileName = $file->getClientOriginalName();
        // }

        // $fileName = $file->getClientOriginalName();
        // // set temporary folder
        // $folder = uniqid() . "-" . now()->timestamp;

        // image name
        $img_name = "test-my-image-name";

        // process the upload and save the file in storage
        // returns false if image does not save
        // $path = $file->storeAs('tmp/' . $folder, $fileName);
        $path = $file->storeAs(
            "images/site-" . $siteId,
            $img_name . "." . $file->extension()
        );

        // 500 error if not saved or if path does not exist
        if (!$path) {
            return response()->json(
                ["error" => "The file could not be saved"],
                500
            );
        }

        //TODO: use composer package for image size: Intervention Image: https://image.intervention.io/v2/introduction/installation

        // Image eloquent
        MediaLibrary::create([
            "user_id" => $request->user_id,
            "team_id" => $team->id,
            "name" => null,
            "path" => $path,
            "size" => $file->getSize(),
            "width" => 1000,
            "height" => 1000,
        ]);

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
