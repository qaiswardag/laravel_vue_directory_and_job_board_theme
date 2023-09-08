<?php

namespace App\Console\Commands;

use App\Models\Job\Job;
use App\Models\Job\JobCountry;
use Illuminate\Console\Command;
use Exception;

class JobCountryRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-country-relations";

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
        $totalJobs = Job::count();

        // Define the number of times to run the code
        $numberOfRuns = 1;

        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalJobs) as $num) {
                $job = Job::find($num);

                if ($job !== null) {
                    // Get a random category that is not already associated with the job
                    $randomCountry = JobCountry::inRandomOrder()
                        ->whereNotIn("id", $job->Countries->pluck("id"))
                        ->first();

                    if ($randomCountry !== null) {
                        // Attach the category to the job
                        $job->Countries()->attach($randomCountry->id);
                    }

                    if ($randomCountry === null) {
                        throw new Exception(
                            "Unable to find a unique random state."
                        );
                    }
                }

                if ($job === null) {
                    throw new Exception("Unable to find the Job with ID $num.");
                }
            }
        }

        $this->info(
            "Seeded successfully, new relations between Jobs & Job Countries for production."
        );
    }
}
