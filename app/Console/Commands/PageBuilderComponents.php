<?php

namespace App\Console\Commands;

use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use Illuminate\Console\Command;

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
        $components = [
            [
                "title" => "title 1",
                "html_code" => "<div>Component 1 HTML</div>",
                "cover_image_original" => "/app-images/blog/1.jpg",
                "cover_image_thumbnail" => "/app-images/blog/1-thumb.jpg",
                "cover_image_medium" => "/app-images/blog/1-medium.jpg",
                "cover_image_large" => "/app-images/blog/1-large.jpg",
            ],
            [
                "title" => "title 2",
                "html_code" => "<div>Component 2 HTML</div>",
                "cover_image_original" => "/app-images/blog/2.jpg",
                "cover_image_thumbnail" => "/app-images/blog/2-thumb.jpg",
                "cover_image_medium" => "/app-images/blog/2-medium.jpg",
                "cover_image_large" => "/app-images/blog/2-large.jpg",
            ],
            // Add data for other components here
        ];

        foreach ($components as $component) {
            PageBuilderComponent::factory()->create($component);
        }

        $this->info("Components seeded successfully.");
    }
}
