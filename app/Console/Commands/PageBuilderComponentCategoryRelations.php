<?php

namespace App\Console\Commands;

use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategory;
use Illuminate\Console\Command;
use Exception;

class PageBuilderComponentCategoryRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:page-builder-component-category-relations";

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
        $totalComponents = PageBuilderComponent::count();

        // Iterate through components and associate them with categories
        foreach (range(1, $totalComponents) as $num) {
            $component = PageBuilderComponent::find($num);

            if ($component !== null) {
                // Get a random category
                $randomCategory = PageBuilderComponentCategory::inRandomOrder()->first();

                if ($randomCategory !== null) {
                    // Assign the category to the component
                    $component->categories()->attach($randomCategory->id);
                }

                if ($randomCategory === null) {
                    throw new Exception("Unable to find a random category.");
                }
            }
            if ($component === null) {
                throw new Exception(
                    "Unable to find the component with ID $num."
                );
            }
        }

        $this->info("Components and categories relations seeded successfully.");
    }
}
