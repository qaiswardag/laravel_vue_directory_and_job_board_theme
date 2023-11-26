<?php

namespace App\Console\Commands;

use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Superadmin\PageBuilder\PageBuilderCoverImageRelation;
use App\PageBuilderComponents\PageBuilderComponentsTemplates;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PageBuilderComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:page-builder-components";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command description";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create an instance of PageBuilderComponentsTemplates
        $pageBuilderTemplates = new PageBuilderComponentsTemplates();

        // Call the index function to get the array
        $pageBuilderComponents = $pageBuilderTemplates->index();

        foreach ($pageBuilderComponents as $component) {
            PageBuilderComponent::factory()->create($component);

            //
            //
            //
            //
            //
            //
        }
        //
        //
        //
        //
        //
        //
        // fake images for components # start
        $directory = storage_path(
            "app/public/uploads/components/single_component_cover_image"
        );

        // Get a list of all files in the directory (excluding directories and hidden files)
        $files = array_filter(scandir($directory), function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === "jpg";
        });

        foreach ($files as $file) {
            $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);

            $path = "components/single_component_cover_image/{$filenameWithoutExtension}";

            // Create a new MediaLibrary instance and store it in a variable
            $mediaLibrary = MediaLibrary::factory()->create([
                "user_id" => 1,
                "team_id" => 1,
                "name" => $filenameWithoutExtension,
                "path" => $path . ".jpg",
                "thumbnail_path" => $path . ".jpg",
                "medium_path" => $path . ".jpg",
                "large_path" => $path . ".jpg",
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
                "extension" => "jpg",
            ]);

            $component = PageBuilderComponent::where(
                "id",
                $filenameWithoutExtension
            )->first();

            if ($component) {
                // Use the ID of the created MediaLibrary instance in the relationship
                PageBuilderCoverImageRelation::factory()->create([
                    "media_library_id" => $mediaLibrary->id,
                    "component_id" => $component->id,
                    "primary" => 0
                ]);
            }
        }
        // fake images for components # end
        //
        //
        //

        $this->info("Seeded successfully, new Components for production.");
    }
}
