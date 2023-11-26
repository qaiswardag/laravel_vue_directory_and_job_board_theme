<?php

namespace App\Console\Commands;

use App\Models\Superadmin\Superadmin;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TeamsUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:teams-users";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command description";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if the user with ID 1 exists
        if (!User::where("id", 1)->exists()) {
            User::factory()->create([
                "id" => 1,
                "first_name" => "Qais",
                "last_name" => "Wardag",
                "email" => "qw@myself.ae",
                "public" => true,
                "current_team_id" => 2,
                "password" => Hash::make("123456"),
            ]);

            Superadmin::factory()->create([
                "user_id" => 1, // Qais Wardag
                "role" => "admin", // reader, editor, or admin
            ]);
        }
        // Check if the user with ID 1 exists
        if (!User::where("id", 2)->exists()) {
            User::factory()->create([
                "id" => 2,
                "first_name" => "Mie",
                "last_name" => "Mortensen",
                "email" => "mm@myself.ae",

                "public" => true,
                "current_team_id" => 2,
                "password" => Hash::make("123456"),
            ]);

            // Superadmin::factory()->create([
            //     "user_id" => 2,
            //     "role" => "editor", // reader, editor or admin
            // ]);
        }

        // Check if the team with ID 1 exists
        if (!Team::where("id", 1)->exists()) {
            Team::factory()->create([
                "id" => 1,
                "user_id" => User::find(1)->id,
                "name" => "myself",
                "slug" => "myself",
                "public" => true,
                "personal_team" => false,
            ]);
        }
        // Check if the team with ID 2 exists
        if (!Team::where("id", 2)->exists()) {
            Team::factory()->create([
                "id" => 2,
                "user_id" => User::find(2)->id,
                "name" => "Dubai Mall",
                "slug" => "Dubai Mall",
                "public" => true,
                "personal_team" => false,
            ]);
        }


        // Check if the user with ID 1 exists
        if (!User::where("id", 3)->exists()) {
            User::factory()->create([
                "id" => 3,
                "first_name" => "Eliza",
                "last_name" => "Stoenescu",
                "email" => "es@myself.ae",
                "public" => true,
                "current_team_id" => 2,
                "password" => Hash::make("123456"),
            ]);

            Superadmin::factory()->create([
                "user_id" => 3, // Qais Wardag
                "role" => "editor", // reader, editor, or admin
            ]);
        }


        TeamUser::factory()->create([
            "user_id" => User::find(1)->id,
            "team_id" => Team::find(2)->id,
            "role" => "admin",
        ]);

        TeamUser::factory()->create([
            "user_id" => User::find(2)->id,
            "team_id" => Team::find(1)->id,
            "role" => "editor",
        ]);

        TeamUser::factory()->create([
            "user_id" => User::find(2)->id,
            "team_id" => Team::find(2)->id,
            "role" => "editor",
        ]);


        TeamUser::factory()->create([
            "user_id" => User::find(3)->id,
            "team_id" => Team::find(2)->id,
            "role" => "editor",
        ]);

        $this->info("Seeded successfully, new Teams and Users for production");
    }
}
