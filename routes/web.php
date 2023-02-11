<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Users\UserController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return Inertia::render("Home/Home", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
    ]);
})->name("home");

//
// middleware for group of pages
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])
    // group of pages
    ->group(function () {
        // test - start
        // test - start
        // test - start
        // test - start
        //
        //
        Route::get("/docs", function () {
            return Inertia::render("Docs");
        })->name("docs");
        //
        //
        // test - end
        // test - end
        // test - end
        // test - end
        // dashboard
        Route::get("/dashboard", function () {
            return Inertia::render("Dashboard/Dashboard");
        })->name("dashboard");
        // privacy policy
        Route::get("/privacy-policy", function () {
            return Inertia::render("PolicyAndTerms/PrivacyPolicy");
        })->name("privacyPolicy");
    });

//
//
//
//
//
//
//
// posts
// posts
Route::get("/manage/posts", [PostController::class, "index"])->name(
    "posts.index"
);
//
Route::get("/posts/create", [PostController::class, "create"])->name(
    "posts.create"
);
Route::post("/posts/store", [PostController::class, "store"])->name(
    "posts.store"
);
Route::get("/posts/{post}", [PostController::class, "show"])->name("post.show");
//
//
//
// super admin
// super admin

Route::get("/superadmin/dashboard", function () {
    return Inertia::render("Superadmin/Index", []);
})->name("superadmin.index");
// super admin — Users
// super admin - User
Route::get("/superadmin/users", [
    SuperadminUserController::class,
    "index",
])->name("superadmin.users.index");
