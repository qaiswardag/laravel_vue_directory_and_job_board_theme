<?php

namespace App\Console\Commands;

use App\Models\Store\Store;
use App\Models\Store\StoreCategory;
use Illuminate\Console\Command;
use Exception;

class StoreCategoryRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:store-category-relations";

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
        $totalStores = Store::count();

        // Define the number of times to run the code
        $numberOfRuns = 3;

        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalStores) as $num) {
                $store = Store::find($num);

                if ($store !== null) {
                    // Get a random category that is not already associated with the store
                    $randomCategory = StoreCategory::inRandomOrder()
                        ->whereNotIn("id", $store->categories->pluck("id"))
                        ->first();

                    if ($randomCategory !== null) {
                        // Attach the category to the store
                        $store->categories()->attach($randomCategory->id);
                    }

                    if ($randomCategory === null) {
                        throw new Exception(
                            "Unable to find a unique random category."
                        );
                    }
                }

                if ($store === null) {
                    throw new Exception(
                        "Unable to find the Store with ID $num."
                    );
                }
            }
        }

        $this->info(
            "Seeded successfully, new relations between Stores & Store Categories for production."
        );
    }
}
