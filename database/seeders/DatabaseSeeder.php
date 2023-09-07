<?php

namespace Database\Seeders;

use App\Models\Job\Job;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use App\Models\Store\Store;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategory;
use App\Models\Superadmin\Superadmin;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Exception;

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
            "role" => "admin", // reader, editor or admin
        ]);

        Team::factory()->create([
            "id" => 1,
            "user_id" => User::find(1)->id,
            "name" => "myself.ae",
            "public" => true,
            "personal_team" => false,
            "reference_id" => "myself",
        ]);

        // Shaun Pelling
        User::factory()->create([
            "id" => 2,
            "first_name" => "Shaun",
            "last_name" => "Pelling",
            "email" => "sp@myself.ae",

            "public" => false,
            "current_team_id" => 2,
            "password" => Hash::make("123456"),
        ]);

        Superadmin::factory()->create([
            "user_id" => 2, // Shaun Pelling
            "role" => "editor", // reader, editor or admin
        ]);

        Team::factory()->create([
            "id" => 2,
            "user_id" => User::find(2)->id,
            "name" => "Lacoste",
            "public" => true,
            "personal_team" => false,
        ]);

        // Mie Mortensen
        User::factory()->create([
            "id" => 3,
            "first_name" => "Mie",
            "last_name" => "Mortensen",
            "email" => "mm@myself.ae",

            "public" => true,
            "current_team_id" => 3,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 3,
            "user_id" => User::find(3)->id,
            "name" => "adidas",
            "public" => true,
            "personal_team" => false,
        ]);
        // Marilyn Monroe
        User::factory()->create([
            "id" => 4,
            "first_name" => "Marilyn",
            "last_name" => "Monroe",
            "email" => "mjm@myself.ae",
            "current_team_id" => 4,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 4,
            "user_id" => User::find(4)->id,
            "name" => "RCA Records",
            "public" => true,
            "personal_team" => false,
        ]);
        // Oprah Winfrey
        User::factory()->create([
            "id" => 5,
            "first_name" => "Oprah",
            "last_name" => "Winfrey",
            "email" => "ow@myself.ae",
            "current_team_id" => 5,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 5,
            "user_id" => User::find(5)->id,
            "name" => "Harpo Productions Oprah",
            "public" => true,
            "personal_team" => false,
        ]);
        // Ingrid Bergman
        User::factory()->create([
            "id" => 6,
            "first_name" => "Ingrid",
            "last_name" => "Bergman",
            "email" => "ib@myself.ae",
            "current_team_id" => 6,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 6,
            "user_id" => User::find(6)->id,
            "name" => "IBI Interiors",
            "public" => true,
            "personal_team" => false,
        ]);
        //
        // Karl Lagerfeld
        User::factory()->create([
            "id" => 7,
            "first_name" => "Karl",
            "last_name" => "Lagerfeld",
            "email" => "kl@myself.ae",
            "current_team_id" => 1,
            "password" => Hash::make("123456"),
        ]);

        // Users Joining Teams

        // Qais Wardag joining Teams
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling Team
            "user_id" => User::find(1)->id, // Qais Wardag
            "role" => "admin", // editor
        ]);

        TeamUser::factory()->create([
            "team_id" => Team::find(3)->id, // Mie Mortensens Team
            "user_id" => User::find(1)->id, // Qais Wardag
            "role" => "editor", // editor
        ]);

        // Shaun Pelling joined Mie Mortensens team
        TeamUser::factory()->create([
            "team_id" => Team::find(3)->id, // Mie Mortensens
            "user_id" => User::find(2)->id, // Shaun Pelling
            "role" => "admin", // editor
        ]);

        // Users joined Shaun Pelling team
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling
            "user_id" => User::find(2)->id, // Shaun Pelling
            "role" => "admin", // editor
        ]);
        // Mie Mortensen joined Shaun Pelling team
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling
            "user_id" => User::find(3)->id, // Mie Mortensen
            "role" => "reader", // editor
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling
            "user_id" => User::find(7)->id, // Karl Lagerfeld
            "role" => "admin", // editor
        ]);
        // Marilyn, Oprah and Ingrid joined Shaun Pelling team
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling team
            "user_id" => User::find(4)->id, // Marilyn
            "role" => "admin",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling team
            "user_id" => User::find(5)->id, // Oprah
            "role" => "editor",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling team
            "user_id" => User::find(6)->id, // Ingrid
            "role" => "editor",
        ]);

        //
        // Pending invitations
        // Users Pending Team Invitations for Shaun Pelling team
        TeamInvitation::factory()->create([
            "team_id" => 2, // Shaun Pelling
            "email" => "join_Johns_team_1@outlook.com",
            "role" => "admin",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 2, // Shaun Pelling
            "email" => "join_shaun_pellings_2@outlook.com",
            "role" => "editor",
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

        // Create 100 users from id 101 to 201 as user with id 101 is already created
        User::factory(100)->create();

        // add users to Shaun Pellings Team
        foreach (range(20, 100) as $num) {
            $user = User::find($num);

            if ($user !== null) {
                // User exists, proceed with creating team-user relationship
                TeamUser::factory()->create([
                    "team_id" => Team::find(2)->id, // Shaun Pellings team
                    "user_id" => $user->id,
                    "role" => "editor",
                ]);
            }
            if ($user === false) {
                throw new Exception("Unable to add User to John Does Team.");
            }

            if ($num == 50) {
                break;
            }
        }

        // fake resources
        Post::factory(20)->create();
        Job::factory(20)->create();
        Store::factory(20)->create();
    }
}
