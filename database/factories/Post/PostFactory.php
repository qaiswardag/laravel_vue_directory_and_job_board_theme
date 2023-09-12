<?php

namespace Database\Factories\Post;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = substr($this->faker->sentence(rand(2, 10), false), 0, -1); // // delete the dot from last word
        $slug = Str::slug($title, "_");

        $randomUserFromTeam = Team::all()
            ->where("id", rand(1, 2))
            ->first();

        $deletedAt = rand(0, 1) ? Carbon::now() : null;

        $tags = [
            "The Modest",
            "Haute Couture",
            "Couture",
            "Spotlight",
            "Fashion Trends",
            "Designer Wear",
            "High-End Fashion",
            "Luxury Apparel",
            "Runway Fashion",
            "Exclusive Styles",
            "Fashion Show",
            "Designer Labels",
            "Glamour Fashion",
            "Fashion Industry",
            "Runway Collection",
            "Couture",
            "Styles",
            "Spotlight",
            "Attire",
            "Elegant",
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
            "team_id" => 1,
            "user_id" => 1,
            "show_author" => rand(0, 1),
            "deleted_at" => null,
            "title" => $title,
            "slug" => $slug,
            "content" => $this->faker->sentence(rand(20, 60), false),
            "published" => rand(0, 1),
            "tags" => $selectedTagsList,
        ];
    }
}
