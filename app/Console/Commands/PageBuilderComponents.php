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
        $imagePath = null;

        $components = [
            [
                "user_id" => 1,
                "title" => "This one is not published",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 0,
                "cover_image_original" => "$imagePath",
                "cover_image_thumbnail" => "$imagePath",
                "cover_image_medium" => "$imagePath",
                "cover_image_large" => "$imagePath",
            ],
            [
                "user_id" => 1,
                "title" => "Also this one is not published",

                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 0,
                "cover_image_original" => "$imagePath",
                "cover_image_thumbnail" => "$imagePath",
                "cover_image_medium" => "$imagePath",
                "cover_image_large" => "$imagePath",
            ],
            [
                "user_id" => 1,
                "title" => "Hello & Bonjour 3",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
                "cover_image_original" => "$imagePath",
                "cover_image_thumbnail" => "$imagePath",
                "cover_image_medium" => "$imagePath",
                "cover_image_large" => "$imagePath",
            ],
            [
                "user_id" => 1,
                "title" => "Hello & Bonjour 4",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
                "cover_image_original" => "$imagePath",
                "cover_image_thumbnail" => "$imagePath",
                "cover_image_medium" => "$imagePath",
                "cover_image_large" => "$imagePath",
            ],
            [
                "user_id" => 1,
                "title" => "Hello & Bonjour 5",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
                "cover_image_original" => "$imagePath",
                "cover_image_thumbnail" => "$imagePath",
                "cover_image_medium" => "$imagePath",
                "cover_image_large" => "$imagePath",
            ],
            [
                "user_id" => 1,
                "title" => "Hello & Bonjour 6",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
                "cover_image_original" => "$imagePath",
                "cover_image_thumbnail" => "$imagePath",
                "cover_image_medium" => "$imagePath",
                "cover_image_large" => "$imagePath",
            ],
            // Add data for other components here
        ];

        foreach ($components as $component) {
            PageBuilderComponent::factory()->create($component);
        }

        $this->info("Seeded successfully, new Components for production.");
    }
}
