<?php

namespace App\Console\Commands;

use App\Models\Job\JobState;
use App\Models\Job\Job;
use Illuminate\Console\Command;
use Exception;

class JobStateRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-state-relations";

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
        $numberOfRuns = 2;

        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalJobs) as $num) {
                $job = Job::find($num);

                if ($job !== null) {
                    // Get a random category that is not already associated with the job
                    $randomState = JobState::inRandomOrder()
                        ->whereNotIn("id", $job->states->pluck("id"))
                        ->first();

                    if ($randomState !== null) {
                        // Attach the category to the job
                        $job->states()->attach($randomState->id);
                    }

                    if ($randomState === null) {
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
            "Seeded successfully, new relations between Jobs & Job States for production."
        );
    }
}
