<?php

namespace App\Console\Commands;

use App\Models\Store\StoreCategory;
use Illuminate\Console\Command;

class StoreCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:store-categories";

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
        $categories = [
            "Store category 1",
            "Store category 2",
            "Store category 3",
            "Store category 4",
            "Store category 5",
        ];

        foreach ($categories as $categoryName) {
            StoreCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->info(
            "Seeded successfully, new Store Categories for production."
        );
    }
}
