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

        return [
            "team_id" => 1,
            "user_id" => 1,
            "show_author" => rand(0, 1),
            "deleted_at" => null,
            "title" => $title,
            "slug" => $slug,
            "content" => $this->faker->sentence(rand(20, 60), false),
            "published" => rand(0, 1),
            "tags" => "Summer,Event,Art,Design",
        ];
    }
}
