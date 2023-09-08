<?php

namespace App\Console\Commands;

use App\Models\Job\Job as JobJob;
use Illuminate\Console\Command;

class Job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:job";

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
        JobJob::factory(20)->create();

        $this->info("Seeded successfully, new Jobs for production.");
    }
}
