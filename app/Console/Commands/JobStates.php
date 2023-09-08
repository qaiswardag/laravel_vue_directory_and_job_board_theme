<?php

namespace App\Console\Commands;

use App\Models\Job\JobState;
use Illuminate\Console\Command;

class JobStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-states";

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
        $states = [
            "Other",
            "Abu Dhabi",
            "Dubai",
            "Sharjah",
            "Ajman",
            "Umm Al Quwain",
            "Ras Al Khaimah",
            "Fujairah",
        ];

        foreach ($states as $stateName) {
            JobState::factory()->create([
                "name" => $stateName,
            ]);
        }

        $this->info("Seeded successfully, new Job States for production.");
    }
}
