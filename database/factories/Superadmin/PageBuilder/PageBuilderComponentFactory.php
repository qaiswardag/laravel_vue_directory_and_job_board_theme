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
                "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
            "published" => rand(0, 1),
        ];
    }
}
