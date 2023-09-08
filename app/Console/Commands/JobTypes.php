<?php

namespace App\Console\Commands;

use App\Models\Job\JobType;
use Illuminate\Console\Command;

class JobTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-types";

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
        $types = [
            "Full-time",
            "Part-time",
            "Freelance",
            "Temporary",
            "Internship",
            "Remote",
            "Hybrid",
        ];

        foreach ($types as $typeName) {
            JobType::factory()->create([
                "name" => $typeName,
            ]);
        }

        $this->info("Seeded successfully, new Job Types for production.");
    }
}
