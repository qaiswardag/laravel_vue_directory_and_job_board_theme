<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Users\UserController;
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
Route::get("/overview/posts", [PostController::class, "index"])->name(
    "overview.posts.index"
);
Route::get("/overview/posts/post/{id}", [PostController::class, "show"])->name(
    "overview.post.show"
);
//
Route::get("/overview/posts/create", [PostController::class, "create"])->name(
    "overview.posts.create"
);
Route::post("/overview/posts/store", [PostController::class, "store"])->name(
    "overview.posts.store"
);
Route::delete("/overview/posts/post/{id}", [
    PostController::class,
    "destroy",
])->name("overview.posts.destroy");
//
//
//
// super admin
// super admin
// super admin dashboard
Route::get("/superadmin/dashboard", [
    DashboardController::class,
    "index",
])->name("superadmin.dashboard");
// super admin — Users
// super admin - User
Route::get("/superadmin/users", [
    SuperadminUserController::class,
    "index",
])->name("superadmin.users.index");
//
Route::get("/superadmin/users/user/{id}", [
    SuperadminUserController::class,
    "show",
])->name("superadmin.users.show");
//
Route::delete("/superadmin/users/user/{id}", [
    SuperadminUserController::class,
    "destroy",
])->name("superadmin.user.destroy");

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
    $posts = Team::findOrFail(5);
    $posts = $posts->posts()->get(); // is working
    //
    //
    //
    // $posts = Auth::user()->posts();
    return $posts;
});
