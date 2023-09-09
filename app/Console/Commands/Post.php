<?php

namespace App\Console\Commands;

use App\Models\Post\Post as PostPost;
use Illuminate\Console\Command;

class Post extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:post";

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
        PostPost::factory(400)->create();

        $this->info("Seeded successfully, new Posts for production.");
    }
}
