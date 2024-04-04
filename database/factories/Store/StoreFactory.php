<?php

namespace Database\Factories\Store;

use App\Models\Team;
use App\PageBuilderComponents\FactoriesPageBuilderContent;
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

        $factoriesPageBuilderContent = new FactoriesPageBuilderContent();

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

        // Calculate the opening time
        $openingTime = Carbon::today()
            ->setHour(10)
            ->setMinute(0)
            ->setSecond(0)
            ->format("H:i:s");

        // Calculate the closing time
        $closingTime = Carbon::today()
            ->setHour(22)
            ->setMinute(0)
            ->setSecond(0)
            ->format("H:i:s");

        return [
            "team_id" => 2,
            "user_id" => 1,
            "show_author" => 0,
            "deleted_at" => null,
            "title" => $title,
            "slug" => $slug,
            "address" => "42 Hillside Ave, Dubai",
            "floor" => "1",
            "content" => $factoriesPageBuilderContent->index(),
            "published" => 1,
            "tags" => $selectedTagsList,
            "contact_page_url" => "https://www.google.com",
            "brand_website_url" => "https://www.cnn.com",

            "use_team_opening_hours" => true,

            "monday_opening_time" => $openingTime,
            "monday_closing_time" => $closingTime,

            "tuesday_opening_time" => $openingTime,
            "tuesday_closing_time" => $closingTime,

            "wednesday_opening_time" => $openingTime,
            "wednesday_closing_time" => $closingTime,

            "thursday_opening_time" => $openingTime,
            "thursday_closing_time" => $closingTime,

            "friday_opening_time" => $openingTime,
            "friday_closing_time" => $closingTime,

            "saturday_opening_time" => $openingTime,
            "saturday_closing_time" => $closingTime,

            "sunday_opening_time" => $openingTime,
            "sunday_closing_time" => $closingTime,

            "time_zone" => "Asia/Dubai",
        ];
    }
}
