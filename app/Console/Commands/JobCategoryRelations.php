<?php

namespace App\Console\Commands;

use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use Illuminate\Console\Command;
use Exception;

class JobCategoryRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job-category-relations";

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
        $numberOfRuns = rand(2, 4);

        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalJobs) as $num) {
                $job = Job::find($num);

                if ($job !== null) {
                    // Get a random category that is not already associated with the job
                    $randomCategory = JobCategory::inRandomOrder()
                        ->whereNotIn("id", $job->categories->pluck("id"))
                        ->first();

                    if ($randomCategory !== null) {
                        // Attach the category to the job
                        $job->categories()->attach($randomCategory->id);
                    }

                    if ($randomCategory === null) {
                        throw new Exception(
                            "Unable to find a unique random category."
                        );
                    }
                }

                if ($job === null) {
                    throw new Exception("Unable to find the Job with ID $num.");
                }
            }
        }

        $this->info(
            "Seeded successfully, new relations between Jobs & Job Categories for production."
        );
    }
}
