<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Guest\Post\PostController as PostPostController;
use App\Http\Controllers\LoggedIn\Post\PostController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Guest\User\UserController;
use App\Http\Controllers\LoggedIn\MediaLibrary\MediaLibraryController;
use App\Http\Controllers\LoggedIn\User\UserSessionsController;
use App\Http\Middleware\isSuperAdmin;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
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

// Pages that are accessible to everyone who is authenticated
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])
    // group of pages
    ->group(function () {
        Route::get("/dashboard", function () {
            return Inertia::render("Dashboard/Dashboard");
        })->name("dashboard");
        Route::get("/user/profile/update", function () {
            return Inertia::render("Profile/ProfileUpdate/ProfileUpdate");
        })->name("user.profile.update");
        Route::get("/user/profile/password", function () {
            return Inertia::render("Profile/ProfilePassword/ProfilePassword");
        })->name("user.profile.password");

        Route::get("/user/profile/security", [
            UserSessionsController::class,
            "show",
        ])->name("user.profile.security");
        Route::get("/overview/posts/{team}", [
            PostController::class,
            "index",
        ])->name("overview.posts.index");
        // media
        Route::get("/media/{team}", [
            MediaLibraryController::class,
            "index",
        ])->name("media.index");
        // media
        Route::post("/media/store", [
            MediaLibraryController::class,
            "store",
        ])->name("media.store");
    });

// Pages that require can create and update authentication
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.can.create.and.update", // editor, owner, administrator
])->group(function () {
    // posts
    Route::get("/overview/posts/create/{team}", [
        PostController::class,
        "create",
    ])->name("overview.posts.create");
    Route::post("/overview/posts/store", [
        PostController::class,
        "store",
    ])->name("overview.posts.store");
});

// Pages that require can destroy authentication
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.can.destroy", // owner, administrator
])
    // group of pages
    ->group(function () {
        // posts
        Route::delete("/overview/posts/post/{post}/{team}", [
            PostController::class,
            "destroy",
        ])->name("overview.posts.post.destroy");
    });

// Pages for quests that are accessible to everyone
Route::middleware([])
    // group of pages
    ->group(function () {
        Route::get("/docs", function () {
            return Inertia::render("Docs");
        })->name("docs");
        // users
        Route::get("/users", [UserController::class, "index"])->name("users");
        // unique user
        Route::get("/users/user/{user}", [UserController::class, "show"])->name(
            "users.show"
        );
        // posts
        Route::get("/posts", [PostPostController::class, "index"])->name(
            "posts"
        );
        // unique post
        Route::get("/posts/post/{post}", [
            PostPostController::class,
            "show",
        ])->name("posts.show");
    });

// Pages that are accessible only to superadmins
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.is.super.admin", // TODO: uncomment this middleware!
])->group(function () {
    Route::get("/admin/dashboard", [DashboardController::class, "index"])->name(
        "admin.dashboard"
    );
    Route::get("/admin/users", [
        SuperadminUserController::class,
        "index",
    ])->name("admin.users");
    Route::delete("/admin/users/user/{user}", [
        SuperadminUserController::class,
        "destroy",
    ])->name("admin.users.user.destroy");
});

// Pages for test
Route::get("/test-me", function () {
    $team = Team::findOrFail(5);
    return $team;
});
