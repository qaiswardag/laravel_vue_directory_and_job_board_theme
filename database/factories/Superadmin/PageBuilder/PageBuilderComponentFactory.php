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
                "<section><div class=\"w-full pb-24\"><div class=\"mx-auto max-w-7xl px-6 lg:flex lg:px-8\">\n<img class=\"w-full\" src=\"https://images.unsplash.com/photo-1418065460487-3e41a6c84dc5?ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&amp;auto=format&amp;fit=crop&amp;w=2200&amp;q=80\"> <div class=\"mt-12 my-8\"><h3>Hello &amp; Bonjour</h3></div> <div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio.</p></div> </div></div></section>",
            "published" => rand(0, 1),
        ];
    }
}
