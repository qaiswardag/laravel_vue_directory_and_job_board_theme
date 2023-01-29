<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Qais Wardag
        User::factory()->create([
            "id" => 1,
            "name" => "Qais Wardag",
            "email" => "qw@myself.ae",
            "current_team_id" => 1,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 1,
            "user_id" => User::find(1)->id,
            "name" => "Team owened by Qais",
            "personal_team" => 1,
        ]);
        // Shaun Pelling
        User::factory()->create([
            "id" => 2,
            "name" => "Shaun Pelling",
            "email" => "sp@myself.ae",
            "current_team_id" => 2,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 2,
            "user_id" => User::find(2)->id,
            "name" => "Team owened by Shaun",
            "personal_team" => 1,
        ]);

        // Mie Mortensen
        User::factory()->create([
            "id" => 3,
            "name" => "Mie Mortensen",
            "email" => "mm@myself.ae",
            "current_team_id" => 3,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 3,
            "user_id" => User::find(3)->id,
            "name" => "Team owened by Mie",
            "personal_team" => 1,
        ]);

        // Qais Wardag joined Mie Mortensens team
        TeamUser::factory()->create([
            "team_id" => Team::find(3)->id, // Mie Mortensens
            "user_id" => User::find(1)->id, // Qais Wardag
            "role" => "admin", // editor
        ]);

        // Shaun Pelling joined Mie Mortensens team
        TeamUser::factory()->create([
            "team_id" => Team::find(3)->id, // Mie Mortensens team
            "user_id" => User::find(2)->id, // Shaun Pellings
            "role" => "admin", // editor
        ]);

        // Mie Mortensen joined Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(3)->id, // Mie Mortensens team
            "role" => "admin", // editor
        ]);

        // fake users
        User::factory(100)->create();
    }
}
