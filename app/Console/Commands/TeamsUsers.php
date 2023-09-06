<?php

namespace App\Console\Commands;

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
        User::factory()->create([
            "id" => 1,
            "first_name" => "John",
            "last_name" => "Doe",
            "email" => "qw@myself.ae",
            "public" => true,
            "current_team_id" => 1,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 1,
            "user_id" => User::find(1)->id,
            "name" => "Lacoste",
            "public" => true,
            "personal_team" => false,
        ]);

        $this->info("Seeded successfully, new Teams and Users for production");
    }
}
