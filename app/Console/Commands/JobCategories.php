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
            "Accounting",
            "Administration",
            "Analytics",
            "Architecture",
            "Arts & Creative",
            "Beauty",
            "Blogger",
            "Communications",
            "Coordination",
            "Customer Service",
            "Design",
            "Developer",
            "Digital & E-commerce",
            "Education & Research",
            "Entrepreneur",
            "Events",
            "Finance",
            "Franchise",
            "Graphic Design",
            "HR",
            "Influencer",
            "Investor Relations",
            "IT",
            "Legal",
            "MakeUp & Styling",
            "Management",
            "Marketing",
            "Media & Editorial",
            "Merchandising",
            "Model",
            "Optics",
            "Photography & Photo Editing",
            "PR & Branding",
            "Product Design",
            "Product Management",
            "Production",
            "Project Management",
            "Purchasing",
            "Retail",
            "Sales & Wholesale",
            "Shipping & Logistics",
            "Shopping",
            "SoMe",
            "Stock & Supply",
            "Visual Merchandiser",
            "Web & Online",
        ];

        foreach ($categories as $categoryName) {
            JobCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->info("Seeded successfully, new Job Categories for production.");
    }
}
