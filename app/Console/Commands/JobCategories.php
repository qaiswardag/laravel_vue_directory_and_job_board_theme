<?php

namespace App\Console\Commands;

use App\Models\Job\JobCategory;
use Illuminate\Console\Command;

class JobCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-categories";

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
            "Other",
            "Administration",
            "Design",
            "Communication",
            "Detail",
            "E-commerce",
            "Graphic Design",
            "Model",
            "Purchasing",
            "Public Relations (PR)",
            "Marketing",
            "Production",
            "Sales",
        ];

        foreach ($categories as $categoryName) {
            JobCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->info("Seeded successfully, new Job Categories for production.");
    }
}
