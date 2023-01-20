<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            "email" => "qais@meeshop.dk",
            "current_team_id" => 1,
            "password" => Hash::make("123456"),
        ]);
    }
}
