<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachUserController;
use App\Http\Controllers\Api\Internal\LoggedIn\DashboardStatsController;
use App\Http\Controllers\Api\Internal\LoggedIn\MediaLibraryController as LoggedInMediaLibraryController;
use App\Http\Controllers\Guests\Job\JobController as JobJobController;
use App\Http\Controllers\LoggedIn\Dashboard\DashboardController as DashboardDashboardController;
use App\Http\Controllers\Guests\Post\PostController as PostPostController;
use App\Http\Controllers\Guests\Store\StoreController as StoreStoreController;
use App\Http\Controllers\LoggedIn\Post\PostController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Guests\User\UserController;
use App\Http\Controllers\LoggedIn\Job\JobController;
use App\Http\Controllers\LoggedIn\MediaLibrary\MediaLibraryController;
use App\Http\Controllers\LoggedIn\Store\StoreController;
use App\Http\Controllers\LoggedIn\User\UserSessionsController;
use App\Http\Controllers\Superadmin\PageBuilder\PageBuilderController;
use App\Http\Controllers\Superadmin\SuperadminManageRoles;
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
    // "ensure.can.read", // reader, editor, owner, administrator
])
    // group of pages
    ->group(function () {
        // USER & DASHBOARD #START
        // USER & DASHBOARD #START
        // USER & DASHBOARD #START
        // USER & DASHBOARD #START
        Route::get("/dashboard/stats/{teamId?}", [
            DashboardStatsController::class,
            "show",
        ])->name("user.dashboard.stats");
        Route::get("/dashboard", [
            DashboardDashboardController::class,
            "show",
        ])->name("dashboard");

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
        // USER & DASHBOARD #END
        // USER & DASHBOARD #END
        // USER & DASHBOARD #END
        // USER & DASHBOARD #END

        // POSTS #START
        // POSTS #START
        // POSTS #START
        // POSTS #START
        Route::get("/team/posts/{referenceId}", [
            PostController::class,
            "index",
        ])->name("team.posts.index");
        // unique post
        Route::get("/team/posts/{referenceId}/post/{post}/{slug}", [
            PostController::class,
            "show",
        ])->name("team.posts.post.show");
        // POSTS #END
        // POSTS #END
        // POSTS #END
        // POSTS #END

        // JOBS #START
        // JOBS #START
        // JOBS #START
        // JOBS #START
        Route::get("/team/jobs/{referenceId}", [
            JobController::class,
            "index",
        ])->name("team.jobs.index");
        // unique post
        Route::get("/team/jobs/{referenceId}/job/{job}/{slug}", [
            JobController::class,
            "show",
        ])->name("team.jobs.job.show");
        // JOBS #END
        // JOBS #END
        // JOBS #END
        // JOBS #END

        // STORE #START
        // STORE #START
        // STORE #START
        // STORE #START
        Route::get("/team/stores/{referenceId}", [
            StoreController::class,
            "index",
        ])->name("team.stores.index");
        // unique post
        Route::get("/team/stores/{referenceId}/store/{store}/{slug}", [
            StoreController::class,
            "show",
        ])->name("team.stores.store.show");
        // STORE #END
        // STORE #END
        // STORE #END
        // STORE #END

        // MEDIA #START
        // MEDIA #START
        // MEDIA #START
        // MEDIA #START
        Route::get("/team/media/index/{team}", [
            LoggedInMediaLibraryController::class,
            "index",
        ])->name("team.media.index");
        // media
        Route::get("/team/media/{referenceId}", [
            MediaLibraryController::class,
            "index",
        ])->name("media.index");
        // MEDIA #END
        // MEDIA #END
        // MEDIA #END
        // MEDIA #END

        // ATTACH USER #START
        // ATTACH USER #START
        // ATTACH USER #START
        // ATTACH USER #START
        Route::get("/team/attach/users/index/{team}", [
            AttachUserController::class,
            "index",
        ])->name("attach.user.index");
        // ATTACH USER #END
        // ATTACH USER #END
        // ATTACH USER #END
        // ATTACH USER #END
    });

// Pages that require can store, create and update authentication
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.can.create.and.update", // editor, owner, administrator
])->group(function () {
    //
    // POSTS #START
    // POSTS #START
    // POSTS #START
    // POSTS #START
    Route::get("/team/posts/create/{referenceId}", [
        PostController::class,
        "create",
    ])->name("team.posts.create");

    Route::get("/team/posts/{referenceId}/post/{post}", [
        PostController::class,
        "edit",
    ])->name("team.posts.post.edit");

    Route::post("/team/posts/post/store/{post}", [
        PostController::class,
        "update",
    ])->name("team.posts.update");
    Route::post("/team/posts/store", [PostController::class, "store"])->name(
        "team.posts.store"
    );
    // POSTS #END
    // POSTS #END
    // POSTS #END
    // POSTS #END

    // JOBS #START
    // JOBS #START
    // JOBS #START
    // JOBS #START
    Route::get("/team/jobs/create/{referenceId}", [
        JobController::class,
        "create",
    ])->name("team.jobs.create");

    Route::get("/team/jobs/{referenceId}/job/{job}", [
        JobController::class,
        "edit",
    ])->name("team.jobs.job.edit");

    Route::post("/team/jobs/job/store/{job}", [
        JobController::class,
        "update",
    ])->name("team.jobs.update");
    Route::post("/team/jobs/store", [JobController::class, "store"])->name(
        "team.jobs.store"
    );
    // JOBS #END
    // JOBS #END
    // JOBS #END
    // JOBS #END

    // STORES #START
    // STORES #START
    // STORES #START
    // STORES #START
    Route::get("/team/stores/create/{referenceId}", [
        StoreController::class,
        "create",
    ])->name("team.stores.create");

    Route::get("/team/stores/{referenceId}/store/{store}", [
        StoreController::class,
        "edit",
    ])->name("team.stores.store.edit");

    Route::post("/team/stores/store/store/{store}", [
        StoreController::class,
        "update",
    ])->name("team.stores.update");
    Route::post("/team/stores/store", [StoreController::class, "store"])->name(
        "team.stores.store"
    );
    // STORES #END
    // STORES #END
    // STORES #END
    // STORES #END

    // MEDIA #START
    // MEDIA #START
    // MEDIA #START
    // MEDIA #START
    Route::get("/team/media/edit/{mediaLibrary}/{team}", [
        LoggedInMediaLibraryController::class,
        "edit",
    ])->name("media.edit");
    Route::post("/team/media/image/update/{team}", [
        MediaLibraryController::class,
        "update",
    ])->name("media.update");
    Route::post("/team/media/store", [
        MediaLibraryController::class,
        "store",
    ])->name("media.store");
    // MEDIA #END
    // MEDIA #END
    // MEDIA #END
    // MEDIA #END
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
        // POSTS #START
        // POSTS #START
        // POSTS #START
        // POSTS #START
        Route::delete("/team/posts/post/{post}/{team}", [
            PostController::class,
            "destroy",
        ])->name("team.posts.post.destroy");
        // POSTS #END
        // POSTS #END
        // POSTS #END
        // POSTS #END

        // JOBS #START
        // JOBS #START
        // JOBS #START
        // JOBS #START
        Route::delete("/team/jobs/job/{job}/{team}", [
            JobController::class,
            "destroy",
        ])->name("team.jobs.job.destroy");
        // JOBS #END
        // JOBS #END
        // JOBS #END
        // JOBS #END

        // STORES #START
        // STORES #START
        // STORES #START
        // STORES #START
        Route::delete("/team/stores/store/{store}/{team}", [
            StoreController::class,
            "destroy",
        ])->name("team.stores.store.destroy");
        // STORES #END
        // STORES #END
        // STORES #END
        // STORES #END

        // MEDIA #START
        // MEDIA #START
        // MEDIA #START
        // MEDIA #START
        Route::post("/team/media/image/destroy/{team}", [
            MediaLibraryController::class,
            "destroy",
        ])->name("media.destroy");
        // MEDIA #END
        // MEDIA #END
        // MEDIA #END
        // MEDIA #END
    });

// Pages for quests that are accessible to everyone
Route::middleware([])
    // group of pages
    ->group(function () {
        // USERS #START
        // USERS #START
        // USERS #START
        // USERS #START
        Route::get("/users", [UserController::class, "index"])->name("users");
        // unique user
        Route::get("/users/{user}", [UserController::class, "show"])->name(
            "users.show"
        );
        // USERS #END
        // USERS #END
        // USERS #END
        // USERS #END

        // POSTS #START
        // POSTS #START
        // POSTS #START
        // POSTS #START
        Route::get("/blog", [PostPostController::class, "index"])->name(
            "blog.index"
        );
        // POSTS #END
        // POSTS #END
        // POSTS #END
        // POSTS #END

        // JOBS #START
        // JOBS #START
        // JOBS #START
        // JOBS #START
        Route::get("/jobs", [JobJobController::class, "index"])->name(
            "jobs.index"
        );
        // JOBS #END
        // JOBS #END
        // JOBS #END
        // JOBS #END

        // STORES #START
        // STORES #START
        // STORES #START
        // STORES #START
        Route::get("/store", [StoreStoreController::class, "index"])->name(
            "stores.index"
        );
        // STORES #END
        // STORES #END
        // STORES #END
        // STORES #END
    });

// ADMIN ONLY #START
// ADMIN ONLY #START
// ADMIN ONLY #START
// ADMIN ONLY #START
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
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

    Route::post("/admin/users/user/create-superadmin/{user}", [
        SuperadminManageRoles::class,
        "create",
    ])->name("admin.users.user.create.superadmin");
    Route::put("/admin/users/user/update-superadmin/{user}", [
        SuperadminManageRoles::class,
        "update",
    ])->name("admin.users.user.update.superadmin");
    Route::delete("/admin/users/user/update-superadmin/{user}", [
        SuperadminManageRoles::class,
        "destroy",
    ])->name("admin.users.user.remove.superadmin");

    // ADMIN ONLY #PAGE BUILDER #START
    // ADMIN ONLY #PAGE BUILDER #START
    // ADMIN ONLY #PAGE BUILDER #START
    // ADMIN ONLY #PAGE BUILDER #START
    Route::get("/admin/components", [
        PageBuilderController::class,
        "index",
    ])->name("admin.components");
    // ADMIN ONLY #PAGE BUILDER #END
    // ADMIN ONLY #PAGE BUILDER #END
    // ADMIN ONLY #PAGE BUILDER #END
    // ADMIN ONLY #PAGE BUILDER #END

    // ADMIN ONLY #END
    // ADMIN ONLY #END
    // ADMIN ONLY #END
    // ADMIN ONLY #END
});
