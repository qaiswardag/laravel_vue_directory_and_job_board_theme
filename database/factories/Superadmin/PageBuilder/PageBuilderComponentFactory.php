<?php

namespace Database\Factories\Superadmin\PageBuilder;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Superadmin\PageBuilder\PageBuilderComponent>
 */
class PageBuilderComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = substr($this->faker->sentence(rand(2, 10), false), 0, -1); // // delete the dot from last word

        return [
            "user_id" => 1,
            "title" => $title,
            "html_code" =>
                "<section <div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4\"><div class=\"mx-auto max-w-7xl\" element=\"\"><div class=\"myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2\" element=\"\"> <div class=\"flex-1 py-2\" element=\"\"> <img class=\"object-cover w-full object-top aspect-square smooth-transition\" src=\"/storage/uploads/fake-images/stores/13.jpg\" alt=\"provider\" element=\"\" hovered=\"\"> <div class=\"break-all py-2\" element=\"\"><p element=\"\">Layouts and visual.</p><p element=\"\">Ullamcorper dignissim cras tincidunt.</p></div> </div> <div class=\"flex-1 py-2\" element=\"\"> <img class=\"object-cover w-full object-top aspect-square smooth-transition\" src=\"/storage/uploads/fake-images/stores/14.jpg\" alt=\"provider\" element=\"\"> <div class=\"break-all py-2\" element=\"\"><p element=\"\">Layouts and visual.</p><p element=\"\">Ullamcorper dignissim cras tincidunt.</p></div> </div>   </div> </div> </div></section>",
            "published" => rand(0, 1),
        ];
    }
}
