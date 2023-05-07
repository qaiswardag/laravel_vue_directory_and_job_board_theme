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
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team)
    {
        $this->authorize("can-read", $team);

        $images = $team
            ->media()
            ->latest()
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
            $extension = $image->getClientOriginalExtension();

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

                // generate a unique ID for the image
                $randomString = Str::random(rand(8, 14)) . strval($timestamp);
                // convert the random string to lowercase using strtolower()
                $randomString = strtolower($randomString);

                // path
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

            // dd("image is:", $image);

            $image->storeAs("uploads", $path, ["disk" => "public"]);

            // Save additional image sizes - start
            // Save additional image sizes - start
            // Save additional image sizes - start

            // Create thumbnail, medium, and large image sizes
            $sizes = [
                "thumbnail" => [200, 200],
                "medium" => [600, 600],
                "large" => [1024, 1024],
            ];

            $imagePaths = [
                "path" => $path,
                "thumbnail_path" => null,
                "medium_path" => null,
                "large_path" => null,
            ];

            foreach ($sizes as $sizeName => [$width, $height]) {
                $resizedImagePath =
                    $teamReferenceId .
                    "/" .
                    $currentYearYear .
                    "/" .
                    $currentMonth .
                    "/" .
                    $slugifyFilename .
                    "_" .
                    $randomString .
                    "_" .
                    $sizeName .
                    "." .
                    $extension;

                $imageType = exif_imagetype(
                    storage_path("app/public/uploads/" . $path)
                );

                // Check if the image can be resized using Image Intervention
                if (
                    $imageType === 2 || // IMAGETYPE_JPEG
                    $imageType === 3 || // IMAGETYPE_PNG
                    $imageType === 6 || // IMAGETYPE_BMP
                    $imageType === 18 // IMAGETYPE_WEBP
                ) {
                    // Create a new Image Intervention instance for the original image
                    $img = Image::make(
                        storage_path("app/public/uploads/" . $path)
                    );

                    // Resize the image, maintaining aspect ratio and allowing upsizing
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    // Save the resized image to storage
                    $img->save(
                        storage_path("app/public/uploads/" . $resizedImagePath)
                    );

                    // Add the path to the resized image to the array of image paths
                    $imagePaths[$sizeName . "_path"] = $resizedImagePath;
                } else {
                    // If the image cannot be resized, use the original image for all image sizes
                    $imagePaths = [
                        "path" => $path,
                        "thumbnail_path" => $path,
                        "medium_path" => $path,
                        "large_path" => $path,
                    ];
                }
            }

            // Save additional image sizes - end
            // Save additional image sizes - end
            // Save additional image sizes - end

            // file size
            $fileSizeKb = intval($image->getSize() / 1024);

            $width = null;
            $height = null;

            // check if file exists
            if (
                File::exists(storage_path("app/public/uploads/" . $path)) ===
                true
            ) {
                // The exif_imagetype function is a built-in PHP function that returns the image
                // type of a file. In this case, the function is used to check if the file located
                // at the path "uploads/" . $path is an image file. If the function returns a value
                // that is not false, it means that the file is an image file, and the condition
                // will evaluate to true.
                $imageType = exif_imagetype(
                    storage_path("app/public/uploads/" . $path)
                );
                if ($imageType !== false) {
                    list($width, $height) = getimagesize(
                        storage_path("app/public/uploads/" . $path)
                    );
                }
            }

            $width = intval($width);
            $height = intval($height);

            // dd($imagePaths);
            // [
            // "path" => "mpfqmmxwoido/2023/05/aeoa_op_epflooub1683441635.jpg"
            // "thumbnail_path" => "mpfqmmxwoido/2023/05/aeoa_op_epflooub1683441635_thumbnail.jpg"
            // "medium_path" => "mpfqmmxwoido/2023/05/aeoa_op_epflooub1683441635_medium.jpg"
            // "large_path" => "mpfqmmxwoido/2023/05/aeoa_op_epflooub1683441635_large.jpg"
            // ]

            // dd($imagePaths["thumbnail_path"]);
            // "mpfqmmxwoido/2023/05/aeoa_op_ldlcnnuarm1683441507_thumbnail.jpg"

            // Image eloquent
            MediaLibrary::create([
                "user_id" => $request->user_id,
                "team_id" => $team->id,
                "name" => null,
                "path" => $imagePaths["path"],
                "thumbnail_path" => $imagePaths["thumbnail_path"],
                "medium_path" => $imagePaths["medium_path"],
                "large_path" => $imagePaths["large_path"],
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

        // Get the image paths to be deleted
        $imagePaths = [
            "path" => $image->path,
            "thumbnail_path" => $image->thumbnail_path,
            "medium_path" => $image->medium_path,
            "large_path" => $image->large_path,
        ];

        // Delete the files from the public directory
        foreach ($imagePaths as $path) {
            if (
                File::exists(storage_path("app/public/uploads/" . $path)) ===
                true
            ) {
                File::delete(storage_path("app/public/uploads/" . $path));
            }
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
