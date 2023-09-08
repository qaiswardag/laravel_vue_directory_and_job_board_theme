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

// Create Job, Job Categories & Job Categories Relations - start
// Create Job, Job Categories & Job Categories Relations - start
Artisan::command("app:seed-job", function () {
    //
})->describe("Seed new Jobs");

Artisan::command("app:seed-job-categories", function () {
    //
})->describe("Seed new Job Categories");

Artisan::command("app:seed-job-category-relations", function () {
    //
})->describe("Seed new Job Categories Relations");
// Create Job, Job Categories & Job Categories Relations - end
// Create Job, Job Categories & Job Categories Relations - end

// Create Job Types & Job Types Relations - start
// Create Job Types & Job Types Relations - start

Artisan::command("app:seed-job-types", function () {
    //
})->describe("Seed new Job Types");

Artisan::command("app:seed-job-type-relations", function () {
    //
})->describe("Seed new Job Types Relations");
// Create Job Types & Job Types Relations - end
// Create Job Types & Job Types Relations - end

// Create Job States & Job States Relations - start
// Create Job States & Job States Relations - start

Artisan::command("app:seed-job-states", function () {
    //
})->describe("Seed new Job States");

Artisan::command("app:seed-job-state-relations", function () {
    //
})->describe("Seed new Job States Relations");
// Create Job States & Job States Relations - end
// Create Job States & Job States Relations - end
