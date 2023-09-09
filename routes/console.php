<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategory;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command("inspire", function () {
    $this->comment(Inspiring::quote());
})->purpose("Display an inspiring quote");

Artisan::command("app:seed-page-builder-components", function () {
    //
})->describe("Seed components");

Artisan::command("app:seed-page-builder-component-categories", function () {
    //
})->describe("Seed component categories");

// Create Teams and Users
Artisan::command("app:seed-teams-users", function () {
    //
})->describe("Seed new Teams and Users for production");

// Create Jobs, Job Categories, Job Types, Job States, Job Countries
Artisan::command("app:seed-job", function () {
    //
})->describe("Seed new Jobs");

Artisan::command("app:seed-job-categories", function () {
    //
})->describe("Seed new Job Categories");

Artisan::command("app:seed-job-category-relations", function () {
    //
})->describe("Seed new Job Categories Relations");

Artisan::command("app:seed-job-types", function () {
    //
})->describe("Seed new Job Types");

Artisan::command("app:seed-job-type-relations", function () {
    //
})->describe("Seed new Job Types Relations");

Artisan::command("app:seed-job-states", function () {
    //
})->describe("Seed new Job States");

Artisan::command("app:seed-job-state-relations", function () {
    //
})->describe("Seed new Job States Relations");

Artisan::command("app:seed-job-countries", function () {
    //
})->describe("Seed new Job Countries");

Artisan::command("app:seed-job-country-relations", function () {
    //
})->describe("Seed new Job Countries Relations");

// Create Stores, Store Categories, Store States
Artisan::command("app:seed-store", function () {
    //
})->describe("Seed new Stores");

Artisan::command("app:seed-store-states", function () {
    //store-state
})->describe("Seed new Store States");

Artisan::command("app:seed-store-state-relations", function () {
    //
})->describe("Seed new Store States Relations");

Artisan::command("app:seed-store-categories", function () {
    //
})->describe("Seed new Store Categories");

Artisan::command("app:seed-store-category-relations", function () {
    //
})->describe("Seed new Store Category Relations");

// Create Posts and Post Categories

Artisan::command("app:seed-post", function () {
    //
})->describe("Seed new Posts");

Artisan::command("app:seed-post-category-relations", function () {
    //
})->describe("Seed new Post Category Relations");
