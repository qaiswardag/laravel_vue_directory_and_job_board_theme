<?php

namespace App\Console\Commands;

use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use Illuminate\Console\Command;
use Exception;

class PostCategoryRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:post-category-relations";

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
        $totalPost = Post::count();

        // Define the number of times to run the code
        $numberOfRuns = rand(2, 4);

        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $totalPost) as $num) {
                $post = Post::find($num);

                if ($post !== null) {
                    // Get a random category that is not already associated with the post
                    $randomCategory = PostCategory::inRandomOrder()
                        ->whereNotIn("id", $post->categories->pluck("id"))
                        ->first();

                    if ($randomCategory !== null) {
                        // Attach the category to the post
                        $post->categories()->attach($randomCategory->id);
                    }

                    if ($randomCategory === null) {
                        throw new Exception(
                            "Unable to find a unique random category."
                        );
                    }
                }

                if ($post === null) {
                    throw new Exception(
                        "Unable to find the Post with ID $num."
                    );
                }
            }
        }

        $this->info(
            "Seeded successfully, new relations between Posts & Post Categories for production."
        );
    }
}
