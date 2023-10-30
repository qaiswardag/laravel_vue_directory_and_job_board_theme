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
            //
            "<section data-componentid=\"1000\"><div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4\"><div class=\"mx-auto max-w-7xl\"><div class=\"myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2\"> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-square smooth-transition\" src=\"/storage/uploads/components/component_html_element_images/4.jpg\" alt=\"provider\"> <div class=\"break-all py-2\"><p>Layouts and visual.</p><p>Ullamcorper dignissim cras tincidunt.</p></div> </div> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-square smooth-transition\" src=\"/storage/uploads/components/component_html_element_images/2.jpg\" alt=\"provider\"> <div class=\"break-all py-2\"><p>Layouts and visual.</p><p>Ullamcorper dignissim cras tincidunt.</p></div> </div>   </div> </div> </div></section>
            <section data-componentid=\"1001\"><div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4\"><div class=\"mx-auto max-w-7xl\"><div class=\"myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2\"> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-square smooth-transition\" src=\"/storage/uploads/components/component_html_element_images/3.jpg\" alt=\"provider\"> <div class=\"break-all py-2\"><p>Layouts and visual.</p><p>Ullamcorper dignissim cras tincidunt.</p></div> </div> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-square smooth-transition\" src=\"/storage/uploads/components/component_html_element_images/1.jpg\" alt=\"provider\"> <div class=\"break-all py-2\"><p>Layouts and visual.</p><p>Ullamcorper dignissim cras tincidunt.</p></div> </div>   </div> </div> </div></section>";

        return [
            "team_id" => 2,
            "user_id" => 1,
            "show_author" => 0,
            "deleted_at" => null,
            "title" => $title,
            "slug" => $slug,
            "address" => "42 Hillside Ave, Dubai",
            "floor" => "1",
            "content" => $content,
            "published" => 1,
            "tags" => $selectedTagsList,
        ];
    }
}
