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
            "Accounting & Payroll",
            "Administration",
            "Analytics",
            "Architecture & Scenography",
            "Arts & Creative",
            "Beauty",
            "Business Development & Planning",
            "Coordination",
            "Customer Service",
            "Design",
            "Digital & E-commerce",
            "Education & Research",
            "Entrepreneur",
            "Events",
            "Finance",
            "Franchise",
            "Graphic Design",
            "HR & Training",
            "Influencer & Blogger",
            "Investor Relations",
            "IT & Support",
            "Legal",
            "MakeUp & Styling",
            "Management",
            "Marketing & Communications",
            "Media & Editorial",
            "Merchandising & Allocation",
            "Model",
            "Optics",
            "Other Positions",
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
            "Visual Merchandising",
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
