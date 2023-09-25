<?php

namespace Database\Factories\Store;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store\Store>
 */
class StoreFactory extends Factory
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

        $randomUserFromTeam = Team::all()
            ->where("id", rand(1, 2))
            ->first();

        $deletedAt = rand(0, 1) ? Carbon::now() : null;

        $tags = [
            "Fashion Trends",
            "Haute Couture",
            "Street Style",
            "Designer Wear",
            "Fashion Week",
            "Luxury Brands",
            "Runway Fashion",
            "Vintage Fashion",
            "Fashion Photography",
            "Fashion Modeling",
            "Fashion Accessories",
            "Fashion Retail",
            "Fashion Styling",
            "Fashion Magazines",
            "Fashion Influencers",
            "Fashion Illustration",
            "Sustainable Fashion",
            "Fashion Merchandising",
            "Textile Design",
            "Fashion History",
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

        $content =
            "<section><div class=\"w-full pt-32 pb-32\"><div class=\"mx-auto max-w-7xl px-6 lg:flex lg:px-8\">" .
            $this->faker->sentence(rand(20, 60), false) .
            "</div></div></section>";

        return [
            "team_id" => 1,
            "user_id" => 1,
            "show_author" => rand(0, 1),
            "deleted_at" => null,
            "title" => $title,
            "slug" => $slug,
            "address" => "Dubai Mall Fashion Avenue",
            "content" => $content,
            "published" => rand(0, 1),
            "tags" => $selectedTagsList,
        ];
    }
}
