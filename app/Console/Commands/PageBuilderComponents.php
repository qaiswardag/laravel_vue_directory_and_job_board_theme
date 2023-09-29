<?php

namespace App\Console\Commands;

use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\PageBuilderComponents\PageBuilderComponentsTemplates;
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
        // Create an instance of PageBuilderComponentsTemplates
        $pageBuilderTemplates = new PageBuilderComponentsTemplates();

        // Call the index function to get the array
        $pageBuilderComponents = $pageBuilderTemplates->index();

        foreach ($pageBuilderComponents as $component) {
            PageBuilderComponent::factory()->create($component);
        }

        $this->info("Seeded successfully, new Components for production.");

        // PageBuilderComponent::factory(200)->create();
    }
}
