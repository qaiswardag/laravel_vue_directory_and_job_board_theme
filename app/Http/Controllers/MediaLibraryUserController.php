<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoggedIn\MediaLibrary\StoreMediaLibraryRequest;
use App\Models\MediaLibraryUser;
use Illuminate\Http\Request;

use App\Http\Requests\LoggedIn\User\StorePaymentMethodsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class MediaLibraryUserController extends Controller
{
    // Define $imagePaths as a property current class
    private $imagePaths;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
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
    public function store(StoreMediaLibraryRequest $request)
    {
        $user = Auth::user();

        foreach ($request->images as $image) {
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

            $path = $this->generateUniqueImagePath(
                "user" . "_" . $user->id,
                $currentYearYear,
                $currentMonth,
                $slugifyFilename,
                $randomString,
                $extension,
                $timestamp
            );

            $image->storeAs("uploads", $path, ["disk" => "public"]);

            $this->saveAdditionalImageSizes(
                $path,
                "user" . "_" . $user->id,
                $currentYearYear,
                $currentMonth,
                $slugifyFilename,
                $randomString,
                $extension
            );

            // file size
            $fileSizeKb = intval($image->getSize() / 1024);

            $width = null;
            $height = null;

            list($width, $height) = $this->getImageDimensions($path);

            $width = intval($width);
            $height = intval($height);

            // Image eloquent
            MediaLibraryUser::create([
                "user_id" => $request->user_id,
                "name" => null,
                "path" => $this->imagePaths["path"],
                "thumbnail_path" => $this->imagePaths["thumbnail_path"],
                "medium_path" => $this->imagePaths["medium_path"],
                "large_path" => $this->imagePaths["large_path"],
                "size" => $fileSizeKb,
                "width" => $width,
                "height" => $height,
                "extension" => $extension,
            ]);
        }

        return redirect()
            ->back()
            ->with("success", "Successfully uploaded images.");
    }

    /**
     * Get image dimensions
     */
    private function getImageDimensions($path)
    {
        $width = null;
        $height = null;

        if (
            File::exists(storage_path(self::PUBLIC_UPLOAD_PATH . $path)) ===
            true
        ) {
            $imageType = exif_imagetype(
                storage_path(self::PUBLIC_UPLOAD_PATH . $path)
            );
            if ($imageType !== false) {
                list($width, $height) = getimagesize(
                    storage_path(self::PUBLIC_UPLOAD_PATH . $path)
                );
            }
        }
        return [$width, $height];
    }

    /**
     * Generate unique image path
     */
    private function generateUniqueImagePath(
        $userId,
        $currentYearYear,
        $currentMonth,
        $slugifyFilename,
        $randomString,
        $extension,
        $timestamp
    ) {
        $path =
            $userId .
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
        while (MediaLibraryUser::where("path", $path)->exists()) {
            // if the path already exists, regenerate the path

            // generate a unique ID for the image
            $randomString = Str::random(rand(8, 14)) . strval($timestamp);
            // convert the random string to lowercase using strtolower()
            $randomString = strtolower($randomString);

            // regenerate the path
            $path =
                $userId .
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

        return $path;
    }

    /**
     * Generating and saving additional sizes
     * of an image.
     */
    private function saveAdditionalImageSizes(
        $path,
        $userId,
        $currentYearYear,
        $currentMonth,
        $slugifyFilename,
        $randomString,
        $extension
    ) {
        // Create thumbnail, medium, and large image sizes
        $sizes = [
            "thumbnail" => [100, 100],
            "medium" => [600, 600],
            "large" => [2048, 2048],
        ];

        $this->imagePaths = [
            "path" => $path,
            "thumbnail_path" => null,
            "medium_path" => null,
            "large_path" => null,
        ];

        foreach ($sizes as $sizeName => [$width, $height]) {
            $resizedImagePath =
                $userId .
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
                storage_path(self::PUBLIC_UPLOAD_PATH . $path)
            );

            // dd($imageType);
            // Check if the original uploaded image can be resized using Image Intervention
            // ERROR: Unsupported image type is image/avif. GD driver is only able to decode JPG, PNG, GIF, BMP or WebP files.
            if (
                $imageType === 1 || // IMAGETYPE_GIF
                $imageType === 2 || // IMAGETYPE_JPEG
                $imageType === 3 || // IMAGETYPE_PNG
                $imageType === 6 || // IMAGETYPE_BMP
                $imageType === 18 // IMAGETYPE_WEBP

                // ERROR: Unsupported image type is image/avif.
                // GD driver is only able to decode JPG, PNG, GIF, BMP or WebP files.
                // $imageType === 19 // AVIF
            ) {
                // Create a new Image Intervention instance for the original image
                $img = Image::make(
                    storage_path(self::PUBLIC_UPLOAD_PATH . $path)
                );

                // Resize the image, maintaining aspect ratio and allowing upsizing
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Save the resized image to storage
                $img->save(
                    storage_path(self::PUBLIC_UPLOAD_PATH . $resizedImagePath)
                );

                // Add the path to the resized image to the array of image paths
                $this->imagePaths[$sizeName . "_path"] = $resizedImagePath;
            } else {
                // If the image cannot be resized, use the original image for all image sizes
                $this->imagePaths = [
                    "path" => $path,
                    "thumbnail_path" => $path,
                    "medium_path" => $path,
                    "large_path" => $path,
                ];
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaLibraryUser $mediaLibraryUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaLibraryUser $mediaLibraryUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            "name" => ["string", "min:2", "max:255", "nullable"],
        ]);

        $image = MediaLibraryUser::findOrFail($request->image_id);
        $image->name = $request->name;
        $image->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $image = MediaLibraryUser::findOrFail($request->image_id);

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
                File::exists(storage_path(self::PUBLIC_UPLOAD_PATH . $path)) ===
                true
            ) {
                File::delete(storage_path(self::PUBLIC_UPLOAD_PATH . $path));
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
