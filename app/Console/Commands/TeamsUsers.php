<?php

namespace App\Console\Commands;

use App\Models\Superadmin\Superadmin;
use App\Models\Team;
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
                "current_team_id" => 1,
                "password" => Hash::make("123456"),
            ]);

            Superadmin::factory()->create([
                "user_id" => 1, // Qais Wardag
                "role" => "admin", // reader, editor, or admin
            ]);
        }

        // Check if the team with ID 1 exists
        if (!Team::where("id", 1)->exists()) {
            Team::factory()->create([
                "id" => 1,
                "user_id" => User::find(1)->id,
                "name" => "myself",
                "public" => true,
                "personal_team" => false,
                "reference_id" => "123456789",
            ]);
        }

        $this->info("Seeded successfully, new Teams and Users for production");
    }
}
