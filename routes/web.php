<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Users\UserController;
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

// group: users who can read
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])
    // group of pages
    ->group(function () {
        // dashboard
        Route::get("/dashboard", function () {
            return Inertia::render("Dashboard/Dashboard");
        })->name("dashboard");

        //
    });
//
// group: users within a team who can create, update and delete
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    "ensure.has.correct.role",
])
    // group of pages
    ->group(function () {
        // posts
        Route::get("/overview/posts/create", [
            PostController::class,
            "create",
        ])->name("overview.posts.create");
        Route::post("/overview/posts/store", [
            PostController::class,
            "store",
        ])->name("overview.posts.store");
        Route::delete("/overview/posts/post/{post}", [
            PostController::class,
            "destroy",
        ])->name("overview.posts.destroy");
        //
        //
        // posts
        Route::get("/overview/posts", [PostController::class, "index"])->name(
            "overview.posts.index"
        );
    });

//
//
// group: super admin
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    "ensure.is.super.admin",
])
    // group of pages
    ->group(function () {
        // super admin dashboard
        Route::get("/admin/dashboard", [
            DashboardController::class,
            "index",
        ])->name("admin.dashboard");
        // super admin — Users
        // super admin - User
        Route::get("/admin/users", [
            SuperadminUserController::class,
            "index",
        ])->name("admin.users.index");
        //
        //
        Route::delete("/admin/users/user/{user}", [
            SuperadminUserController::class,
            "destroy",
        ])->name("admin.user.destroy");

        //
        //
        //
        //
        //
    });
//
//
//
// guest routes start
// middleware for group of pages
Route::middleware([])
    // group of pages
    ->group(function () {
        Route::get("/docs", function () {
            return Inertia::render("Docs");
        })->name("docs");
        //
        // unique user
        Route::get("/users/user/{user}", [UserController::class, "show"])->name(
            "users.show"
        );
        // unique post
        Route::get("/posts/post/{user}", [PostController::class, "show"])->name(
            "posts.show"
        );

        //
        //
        //
        //
        //
    });
// guest routes end
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
//
//
// test api
Route::get("/test-me", function () {
    // $posts = Post::where("team_id", 5)->paginate(2); // is working
    //
    //
    // $posts = Team::findOrFail(5);
    // $posts = $posts->posts()->paginate(); // is working
    //
    //
    $team = Team::findOrFail(5);
    $posts = $team->posts()->get();

    //
    //
    //
    // $posts = Auth::user()->posts();
    return $posts;
});
