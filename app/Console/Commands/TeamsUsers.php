<?php

namespace App\Console\Commands;

use App\Models\Superadmin\Superadmin;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Carbon\Carbon;
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
        // Calculate the opening time
        $openingTime = Carbon::today()
            ->setHour(10)
            ->setMinute(0)
            ->setSecond(0)
            ->format("H:i:s");

        // Calculate the closing time
        $closingTime = Carbon::today()
            ->setHour(22)
            ->setMinute(0)
            ->setSecond(0)
            ->format("H:i:s");

        // Check if the user with ID 1 exists
        if (!User::where("id", 1)->exists()) {
            User::factory()->create([
                "id" => 1,
                "first_name" => "Qais",
                "last_name" => "Wardag",
                "email" => "qw@myissue.io",
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
                "email" => "mm@myissue.io",

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
                "name" => "MyISSUE",
                "slug" => "MyISSUE",
                "public" => true,
                "personal_team" => false,

                "monday_opening_time" => $openingTime,
                "monday_closing_time" => $closingTime,

                "tuesday_opening_time" => $openingTime,
                "tuesday_closing_time" => $closingTime,

                "wednesday_opening_time" => $openingTime,
                "wednesday_closing_time" => $closingTime,

                "thursday_opening_time" => $openingTime,
                "thursday_closing_time" => $closingTime,

                "friday_opening_time" => $openingTime,
                "friday_closing_time" => $closingTime,

                "saturday_opening_time" => $openingTime,
                "saturday_closing_time" => $closingTime,

                "sunday_opening_time" => $openingTime,
                "sunday_closing_time" => $closingTime,

                "time_zone" => "Asia/Dubai",
            ]);
        }
        // Check if the team with ID 2 exists
        if (!Team::where("id", 2)->exists()) {
            Team::factory()->create([
                "id" => 2,
                "user_id" => User::find(1)->id,
                "name" => "Dubai Mall",
                "slug" => "Dubai Mall",
                "public" => true,
                "personal_team" => false,

                "monday_opening_time" => $openingTime,
                "monday_closing_time" => $closingTime,

                "tuesday_opening_time" => $openingTime,
                "tuesday_closing_time" => $closingTime,

                "wednesday_opening_time" => $openingTime,
                "wednesday_closing_time" => $closingTime,

                "thursday_opening_time" => $openingTime,
                "thursday_closing_time" => $closingTime,

                "friday_opening_time" => $openingTime,
                "friday_closing_time" => $closingTime,

                "saturday_opening_time" => $openingTime,
                "saturday_closing_time" => $closingTime,

                "sunday_opening_time" => $openingTime,
                "sunday_closing_time" => $closingTime,

                "time_zone" => "Asia/Dubai",
            ]);
        }

        // Check if the user with ID 1 exists
        if (!User::where("id", 3)->exists()) {
            User::factory()->create([
                "id" => 3,
                "first_name" => "Eliza",
                "last_name" => "Stoenescu",
                "email" => "es@myissue.io",
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
