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
        $pageBuilderComponents = [
            "user_id" => rand(1, 2),
            "title" => "Misty shroud over a forest",
            "html_code" =>
                "<section><div class=\"w-full pt-32 pb-32\"><div class=\"mx-auto max-w-7xl px-6 lg:flex lg:px-8\">\n<img class=\"w-full\" src=\"https://images.unsplash.com/photo-1418065460487-3e41a6c84dc5?ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&amp;auto=format&amp;fit=crop&amp;w=2200&amp;q=80\"> <div class=\"mt-12 my-8\"><h3>Hello &amp; Bonjour</h3></div> <div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio.</p></div> </div></div></section>",
            "published" => 0,
        ];

        foreach ($pageBuilderComponents as $component) {
            PageBuilderComponent::factory()->create($component);
        }

        PageBuilderComponent::factory(200)->create();

        $this->info("Seeded successfully, new Components for production.");
    }
}
