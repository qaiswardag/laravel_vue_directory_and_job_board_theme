<?php

namespace Database\Factories\Post;

use App\Models\Team;
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
        // random
        // $randomUserFromTeam = Team::all()
        //     ->random()
        //     ->first();
        //
        // specific
        $randomUserFromTeam = Team::all()
            ->where("id", rand(1, 2))
            ->first();
        return [
            "team_id" => $randomUserFromTeam->id, // Qais Wardag, Shaun Pelling, Mie Mortensens Team etc.
            "user_id" => $randomUserFromTeam->user_id, // Qais Wardag, Shaun Pelling or Mie Mortensen,
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
