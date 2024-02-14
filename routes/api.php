<?php

use App\Http\Controllers\Guests\Job\JobController;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Internal\Guest\Features\JobsGuestIndexController;
use App\Http\Controllers\Api\Internal\Guest\Features\PostsGuestIndexController;
use App\Http\Controllers\Api\Internal\Guest\Features\StoresGuestIndexController;
use App\Http\Controllers\Guests\Store\StoreController as StoreStoreController;
use App\Http\Controllers\Guests\Job\JobController as JobJobController;
use App\Http\Controllers\Guests\Post\PostController as PostPostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/jobs", [JobController::class, "jobsFetch"])->name("jobs.fetch");

// index
Route::get("/guest/campaigns/index", [
    PostsGuestIndexController::class,
    "index",
])->name("api.guest.posts.index");

Route::get("/guest/jobs/index", [
    JobsGuestIndexController::class,
    "index",
])->name("api.api.guest.jobs.index");

Route::get("/guest/stores/index", [
    StoresGuestIndexController::class,
    "index",
])->name("api.api.guest.stores.index");

// single
Route::get("{teamSlug}/campaign/{postSlug}/view/{postId}/", [
    PostPostController::class,
    "showAPI",
])->name("posts.guest.show");

Route::get("{teamSlug}/job/{postSlug}/view/{postId}/", [
    JobJobController::class,
    "showAPI",
])->name("jobs.guest.show");

Route::get("{teamSlug}/store/{postSlug}/view/{postId}", [
    StoreStoreController::class,
    "showAPI",
])->name("stores.guest.show");
