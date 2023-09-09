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
            "Accessories",
            "Bags & Leather Goods",
            "Beauty & Skincare",
            "Children's Fashion",
            "Department Stores",
            "Events",
            "Eyewear",
            "Home & Lifestyle",
            "Jewelry",
            "Lingerie & Nightwear",
            "Men's Fashion",
            "Perfumes",
            "Shoes",
            "Sports & Leisure",
            "Tailors",
            "Watches",
            "Wedding & Occasion Wear",
            "Women's Fashion",
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
