<?php

namespace Database\Factories\Post;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            "user_id" => rand(1, 3), // Qais Wardag, Shaun Pelling or Mie Mortensen,
            "team_id" => rand(1, 3), // Qais Wardag, Shaun Pelling or Mie Mortensens Team
            "title" => substr($this->faker->sentence(rand(2, 6), false), 0, -1),
            "slug" => "the-slug",
            "content" => $this->faker->sentence(rand(20, 60), false),
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ];
    }
}
