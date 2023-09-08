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

        // Define the number of times to run the code
        $numberOfRuns = 3;

        // Iterate to run the code multiple times
        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalComponents) as $num) {
                $component = PageBuilderComponent::find($num);

                if ($component !== null) {
                    // Get a random category that is not already associated with the component
                    $randomCategory = PageBuilderComponentCategory::inRandomOrder()
                        ->whereNotIn("id", $component->categories->pluck("id"))
                        ->first();

                    if ($randomCategory !== null) {
                        // Attach the category to the component
                        $component->categories()->attach($randomCategory->id);
                    }

                    if ($randomCategory === null) {
                        throw new Exception(
                            "Unable to find a unique random category."
                        );
                    }
                }

                if ($component === null) {
                    throw new Exception(
                        "Unable to find the component with ID $num."
                    );
                }
            }
        }

        $this->info(
            "Seeded successfully, new relations between Components & Components Categories for production."
        );
    }
}
