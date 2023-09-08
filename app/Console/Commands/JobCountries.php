<?php

namespace App\Console\Commands;

use App\Models\Job\JobCountry;
use Illuminate\Console\Command;

class JobCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-countries";

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
        $countries = [
            "Other",
            "Bahrain",
            "Kuwait",
            "Qatar",
            "Saudi Arabia",
            "Turkey",
            "United Arab Emirates",
        ];

        foreach ($countries as $jobCountry) {
            JobCountry::factory()->create([
                "name" => $jobCountry,
            ]);
        }

        $this->info("Seeded successfully, new Job Countries for production.");
    }
}
