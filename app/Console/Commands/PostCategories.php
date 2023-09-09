<?php

namespace App\Console\Commands;

use App\Models\Post\PostCategory;
use Illuminate\Console\Command;

class PostCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:post-categories";

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
            "Beauty",
            "Business",
            "Careers",
            "Designers",
            "Direct-to-Consumer",
            "Entrepreneurship",
            "Events",
            "Fashion",
            "Fashion Week",
            "Financial Markets",
            "Global Markets",
            "Home",
            "Insights",
            "Jobs",
            "Luxury",
            "Marketing",
            "Media",
            "News",
            "Opinions",
            "People",
            "Podcasts",
            "Retail",
            "Runway",
            "Shop",
            "Sustainability",
            "Technology",
            "The State of Fashion",
            "Trends",
            "UAE",
            "Workplace & Talent",
        ];

        foreach ($categories as $categoryName) {
            PostCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->info("Seeded successfully, new Post Categories for production.");
    }
}
