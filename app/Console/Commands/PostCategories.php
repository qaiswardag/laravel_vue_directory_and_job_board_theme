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
            "Fashion Blog kategori 1",
            "Fashion Blog kategori 2",
            "Fashion Blog kategori 3",
            "Fashion Blog kategori 4",
            "Fashion Blog kategori 5",
            "Fashion Blog kategori 6",
            "Fashion Blog kategori 7",
            "Fashion Blog kategori 8",
            "Fashion Blog kategori 9",
            "Fashion Blog kategori 10",
            "Fashion Blog kategori 11",
        ];

        foreach ($categories as $categoryName) {
            PostCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->info("Seeded successfully, new Post Categories for production.");
    }
}
