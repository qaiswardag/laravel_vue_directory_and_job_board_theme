<?php

namespace Database\Factories\Job;

use App\Models\Team;
use App\PageBuilderComponents\FactoriesPageBuilderContent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = substr($this->faker->sentence(rand(2, 10), false), 0, -1); // // delete the dot from last word
        $slug = Str::slug($title, "_");

        $factoriesPageBuilderContent = new FactoriesPageBuilderContent();

        $randomUserFromTeam = Team::all()
            ->where("id", rand(1, 2))
            ->first();

        $deletedAt = rand(0, 1) ? Carbon::now() : null;

        $tags = [
            "Web Development",
            "Software Engineering",
            "Data Science",
            "Project Management",
            "Graphic Design",
            "Digital Marketing",
            "Content Writing",
            "Customer Support",
            "UX/UI Design",
            "Database Administration",
            "Mobile App Development",
            "Network Security",
            "Sales and Marketing",
            "Machine Learning",
            "Artificial Intelligence",
            "Cybersecurity",
            "Cloud Computing",
            "Financial Analysis",
            "Business Development",
            "Quality Assurance",
        ];

        // You can adjust the range to generate as many records as needed.
        $selectedTags = array_rand($tags, rand(2, 5)); // Randomly select between 2 and 5 tags.

        // Create a comma-separated list of selected tags.
        $selectedTagsList = implode(
            ", ",
            array_map(function ($index) use ($tags) {
                return $tags[$index];
            }, $selectedTags)
        );

        return [
            "team_id" => 2,
            "user_id" => 1,
            "show_author" => 0,
            "deleted_at" => null,
            "title" => $title,
            "slug" => $slug,
            "content" => $factoriesPageBuilderContent->index(),
            "published" => 1,
            "is_filled" => rand(0, 1),
            "tags" => $selectedTagsList,
            "started_at" => Carbon::now(),
            "ended_at" => Carbon::now()->addDays(30),
        ];
    }
}
