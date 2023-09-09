<?php

namespace App\Console\Commands;

use App\Models\Store\Store;
use App\Models\Store\StoreState;
use Illuminate\Console\Command;
use Exception;

class StoreStateRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:store-state-relations";

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
        $numberOfRuns = 1;

        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalStores) as $num) {
                $store = Store::find($num);

                if ($store !== null) {
                    // Get a random states that is not already associated with the store
                    $randomState = StoreState::inRandomOrder()
                        ->whereNotIn("id", $store->states->pluck("id"))
                        ->first();

                    if ($randomState !== null) {
                        // Attach the states to the store
                        $store->states()->attach($randomState->id);
                    }

                    if ($randomState === null) {
                        throw new Exception(
                            "Unable to find a unique random state."
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
            "Seeded successfully, new relations between Stores & Stores States for production."
        );
    }
}
