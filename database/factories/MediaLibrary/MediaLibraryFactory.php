<?php

namespace Database\Factories\MediaLibrary;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaLibrary>
 */
class MediaLibraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => 1,
            "team_id" => 1,
            "path" => "my-path-string",
            "size" => 1000,
            "width" => 1000,
            "height" => 1000,
        ];
    }
}
