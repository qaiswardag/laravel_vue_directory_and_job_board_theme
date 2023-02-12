<?php

namespace Database\Factories\Post;

use App\Models\User;
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
        $randomUser = User::all()
            ->random(1)
            ->first()->id;
        return [
            "user_id" => $randomUser, // Qais Wardag, Shaun Pelling or Mie Mortensen,
            "team_id" => rand(1, 6), // Qais Wardag, Shaun Pelling, Mie Mortensens Team etc.
            "title" => substr(
                $this->faker->sentence(rand(2, 10), false),
                0,
                -1
            ), // delete the dot from last word
            "slug" => "the-slug",
            "content" => $this->faker->sentence(rand(20, 60), false),
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ];
    }
}
