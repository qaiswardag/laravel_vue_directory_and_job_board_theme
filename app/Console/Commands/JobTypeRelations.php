<?php

namespace App\Console\Commands;

use App\Models\Job\JobType;
use App\Models\Job\Job;
use Illuminate\Console\Command;
use Exception;

class JobTypeRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-type-relations";

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
                    $randomType = JobType::inRandomOrder()
                        ->whereNotIn("id", $job->types->pluck("id"))
                        ->first();

                    if ($randomType !== null) {
                        // Attach the category to the job
                        $job->types()->attach($randomType->id);
                    }

                    if ($randomType === null) {
                        throw new Exception(
                            "Unable to find a unique random type."
                        );
                    }
                }

                if ($job === null) {
                    throw new Exception("Unable to find the Job with ID $num.");
                }
            }
        }

        $this->info(
            "Seeded successfully, new relations between Job & Job Types for production."
        );
    }
}
