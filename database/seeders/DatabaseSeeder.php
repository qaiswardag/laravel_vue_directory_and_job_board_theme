<?php

namespace Database\Seeders;

use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
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
            "email" => "mjm@myself.ae",
            "username" => "marilyn.monroe",
            "current_team_id" => 4,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 4,
            "user_id" => User::find(4)->id,
            "name" => "RCA Records",
            "personal_team" => false,
        ]);
        // Oprah Winfrey
        User::factory()->create([
            "id" => 5,
            "first_name" => "Oprah",
            "last_name" => "Winfrey",
            "email" => "ow@myself.ae",
            "username" => "oprah.winfrey",
            "current_team_id" => 5,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 5,
            "user_id" => User::find(5)->id,
            "name" => "Harpo Productions Oprah",
            "personal_team" => false,
        ]);
        // Ingrid Bergman
        User::factory()->create([
            "id" => 6,
            "first_name" => "Ingrid",
            "last_name" => "Bergman",
            "email" => "ib@myself.ae",
            "username" => "ingrid.bergman",
            "current_team_id" => 6,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 6,
            "user_id" => User::find(6)->id,
            "name" => "IBI Interiors",
            "personal_team" => false,
        ]);
        //
        //
        //
        //
        //
        // // Qais, Shaun and Mie user and team creations
        // Qais Wardag
        User::factory()->create([
            "id" => 1,
            "first_name" => "Qais",
            "last_name" => "Wardag",
            "email" => "qw@myself.ae",
            "username" => "qais.wardag",
            "public" => true,
            "current_team_id" => 1,
            "superadmin" => true,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 1,
            "user_id" => User::find(1)->id,
            "name" => "Owened by Qais",
            "personal_team" => false,
        ]);
        // Shaun Pelling
        User::factory()->create([
            "id" => 2,
            "first_name" => "Shaun",
            "last_name" => "Pelling",
            "email" => "sp@myself.ae",
            "username" => "shaun.pelling",
            "public" => false,
            "current_team_id" => 2,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 2,
            "user_id" => User::find(2)->id,
            "name" => "Owened by Shaun",
            "personal_team" => false,
        ]);

        // Mie Mortensen
        User::factory()->create([
            "id" => 3,
            "first_name" => "Mie",
            "last_name" => "Mortensen",
            "email" => "mm@myself.ae",
            "username" => "mie.mortensen",
            "public" => true,
            "current_team_id" => 3,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 3,
            "user_id" => User::find(3)->id,
            "name" => "Owened by Mie",
            "personal_team" => false,
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
        // Shaun Pelling joined joined Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(2)->id, // Shaun Pelling
            "role" => "admin", // editor
        ]);
        // Mie Mortensen joined Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag
            "user_id" => User::find(3)->id, // Mie Mortensen
            "role" => "reader", // editor
        ]);
        // Marilyn, Oprah and Ingrid joined Qais Wardags team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag's team
            "user_id" => User::find(4)->id, // Marilyn
            "role" => "reader",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag's team
            "user_id" => User::find(5)->id, // Oprah
            "role" => "reader",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Qais Wardag's team
            "user_id" => User::find(6)->id, // Ingrid
            "role" => "reader",
        ]);

        //
        // Pending invitations
        //
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

        // fake Users
        User::factory(100)->create();
        // fake Posts
        Post::factory(250)->create();
        // fake media
        // MediaLibrary::factory(100)->create();
        //
        //
        //
        //
        //
        //
        //
        //
        //
        //
        //
        // Post for Oprah Winfrey and here Team
        Post::factory()->create([
            "user_id" => 5, // Oprah Winfrey
            "team_id" => 5, // Oprah Winfrey teem
            "title" => "My Post title for Oprahs first Blog Post",
            "slug" => "the-slug",
            "content" => "The description. This post belongs to Oprahs team",
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ]);
        Post::factory()->create([
            "user_id" => 5, // Oprah Winfrey
            "team_id" => 5, // Oprah Winfrey teem
            "title" => "Oprahs second Blog Post",
            "slug" => "the-slug",
            "content" => "The description. This post belongs to Oprahs team.",
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ]);
        Post::factory()->create([
            "user_id" => 5, // Oprah Winfrey
            "team_id" => 5, // Oprah Winfrey teem
            "title" => "Blog Post title",
            "slug" => "the-slug",
            "content" => "The description. This post belongs to Oprahs team.",
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ]);
        Post::factory()->create([
            "user_id" => 5, // Oprah Winfrey
            "team_id" => 5, // Oprah Winfrey teem
            "title" => "Blog Post title",
            "slug" => "the-slug",
            "content" => "The description. This post belongs to Oprahs team.",
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ]);
        Post::factory()->create([
            "user_id" => 6, // Ingrid Bergman
            "team_id" => 5, // Oprah Winfrey teem
            "title" => "Blog Post title",
            "slug" => "the-slug",
            "content" => "The description. This post belongs to Oprahs team.",
            "published" => rand(0, 1),
            "tags" => "Summer,Dubai,Event,FashionShow",
        ]);
    }
}
