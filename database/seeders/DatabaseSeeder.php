<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamInvitation;
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
        // Random users

        // Marilyn Monroe
        User::factory()->create([
            "id" => 4,
            "first_name" => "Marilyn",
            "last_name" => "Monroe",
            "email" => "marilyn-monroe@myself.ae",
            "current_team_id" => 4,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 4,
            "user_id" => User::find(4)->id,
            "name" => "Loreal",
            "personal_team" => false,
        ]);
        // Oprah Winfrey
        User::factory()->create([
            "id" => 5,
            "first_name" => "Oprah",
            "last_name" => "Winfrey",
            "email" => "oprah-winfrey@myself.ae",
            "current_team_id" => 5,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 5,
            "user_id" => User::find(5)->id,
            "name" => "Loreal",
            "personal_team" => false,
        ]);
        // Marilyn Monroe
        User::factory()->create([
            "id" => 6,
            "first_name" => "Ingrid",
            "last_name" => "Bergman",
            "email" => "ingrid-bergman@myself.ae",
            "current_team_id" => 6,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 6,
            "user_id" => User::find(6)->id,
            "name" => "Loreal",
            "personal_team" => false,
        ]);
        //
        //
        //
        //
        //
        //
        // Qais Wardag
        User::factory()->create([
            "id" => 1,
            "first_name" => "Qais",
            "last_name" => "Wardag",
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
            "first_name" => "Shaun",
            "last_name" => "Pelling",
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
            "first_name" => "Mie",
            "last_name" => "Mortensen",
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

        // Users joined Qais Wardags team
        //
        // Mie Mortensen joined Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(3)->id, // Mie Mortensens team
            "role" => "admin", // editor
        ]);
        // Shaun Pelling joined joined Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(2)->id, // Mie Mortensens team
            "role" => "editor", // editor
        ]);
        // Marilyn, Oprah and Ingrid Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(4)->id, // Mie Mortensens team
            "role" => "editor", // editor
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(5)->id, // Mie Mortensens team
            "role" => "editor", // editor
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(6)->id, // Mie Mortensens team
            "role" => "editor", // editor
        ]);

        // Users Pending Team Invitations for Qais Wardags team
        TeamInvitation::factory()->create([
            "team_id" => 1, // Qais Wardag
            "email" => "join_qaiss_team_1@outlook.com",
            "role" => "admin",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 1, // Qais Wardag
            "email" => "join_qaiss_team_2@outlook.com",
            "role" => "editor",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 1, // Qais Wardag
            "email" => "join_qaiss_team_3@outlook.com",
            "role" => "admin",
        ]);

        // Users Pending Team Invitations for Mie Mortensens team
        TeamInvitation::factory()->create([
            "team_id" => 3, // Mie Mortensen
            "email" => "join_mies_team_1@outlook.com",
            "role" => "admin",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 3, // Mie Mortensen
            "email" => "join_mies_team_2@outlook.com",
            "role" => "editor",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 3, // Mie Mortensen
            "email" => "join_mies_team_3@outlook.com",
            "role" => "admin",
        ]);

        // fake users
        User::factory(100)->create();
    }
}
