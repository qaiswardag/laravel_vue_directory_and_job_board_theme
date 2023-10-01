<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachComponentCategoriesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachJobCategoriesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachJobCountriesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachJobStatesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachJobTypesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachPostCategoriesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachStoreCategoriesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachStoreStatesController;
use App\Http\Controllers\Api\Internal\LoggedIn\AttachUserController;
use App\Http\Controllers\Api\Internal\LoggedIn\DashboardStatsController;
use App\Http\Controllers\Api\Internal\LoggedIn\MediaLibraryController as LoggedInMediaLibraryController;
use App\Http\Controllers\Api\Internal\LoggedIn\PageBuilderComponentsController;
use App\Http\Controllers\Guests\Job\JobController as JobJobController;
use App\Http\Controllers\LoggedIn\Dashboard\DashboardController as DashboardDashboardController;
use App\Http\Controllers\Guests\Post\PostController as PostPostController;
use App\Http\Controllers\Guests\Professional\ProfessionalController;
use App\Http\Controllers\Guests\Store\StoreController as StoreStoreController;
use App\Http\Controllers\LoggedIn\Post\PostController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Guests\User\UserController;
use App\Http\Controllers\LoggedIn\Job\JobController;
use App\Http\Controllers\LoggedIn\MediaLibrary\MediaLibraryController;
use App\Http\Controllers\LoggedIn\Store\StoreController;
use App\Http\Controllers\LoggedIn\User\SubscriptionController;
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
        Route::get("/team/posts/{teamId}", [
            PostController::class,
            "index",
        ])->name("team.posts.index");
        // unique post
        Route::get("/team/posts/{teamId}/post/{post}/{slug}", [
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
        Route::get("/team/jobs/{teamId}", [
            JobController::class,
            "index",
        ])->name("team.jobs.index");
        // unique job
        Route::get("/team/jobs/{teamId}/job/{job}/{slug}", [
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
        Route::get("/team/stores/{teamId}", [
            StoreController::class,
            "index",
        ])->name("team.stores.index");
        // unique store
        Route::get("/team/stores/{teamId}/store/{store}/{slug}", [
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
        Route::get("/team/media/{teamId}", [
            MediaLibraryController::class,
            "index",
        ])->name("media.index");
        // MEDIA #END
        // MEDIA #END
        // MEDIA #END
        // MEDIA #END

        // ATTACH USER, POST CATEGORIES, POST TYPES, JOB CATEGORIES, STORE CATEGORIES #START
        // ATTACH USER #START
        // ATTACH USER #START
        // ATTACH USER #START
        // USERS
        Route::get("/team/attach/users/index/{team}", [
            AttachUserController::class,
            "index",
        ])->name("attach.user.index");

        // COMPONENT CATEGORIES
        Route::get("/team/attach/component/categories/index/{team}", [
            AttachComponentCategoriesController::class,
            "index",
        ])->name("attach.component.categories.index");

        // POST CATEGORIES
        Route::get("/team/attach/post/categories/index/{team}", [
            AttachPostCategoriesController::class,
            "index",
        ])->name("attach.post.categories.index");

        // JOB COUNTRIES
        Route::get("/team/attach/job/countries/index/{team}", [
            AttachJobCountriesController::class,
            "index",
        ])->name("attach.job.countries.index");
        // JOB STATES
        Route::get("/team/attach/job/states/index/{team}", [
            AttachJobStatesController::class,
            "index",
        ])->name("attach.job.states.index");
        // JOB STATES
        Route::get("/team/attach/job/categories/index/{team}", [
            AttachJobCategoriesController::class,
            "index",
        ])->name("attach.job.categories.index");
        // JOB CATEGORIES
        Route::get("/team/attach/job/categories/index/{team}", [
            AttachJobCategoriesController::class,
            "index",
        ])->name("attach.job.categories.index");

        // JOB TYPES
        Route::get("/team/attach/job/types/index/{team}", [
            AttachJobTypesController::class,
            "index",
        ])->name("attach.job.types.index");

        // STORE STATES
        Route::get("/team/attach/store/states/index/{team}", [
            AttachStoreStatesController::class,
            "index",
        ])->name("attach.store.states.index");
        // STORE CATEGORES
        Route::get("/team/attach/store/categories/index/{team}", [
            AttachStoreCategoriesController::class,
            "index",
        ])->name("attach.store.categories.index");
        // ATTACH USER #END
        // ATTACH USER #END
        // ATTACH USER #END
        // ATTACH USER #END

        // PAGE BUILDER #START
        // PAGE BUILDER #START
        // PAGE BUILDER #START
        // PAGE BUILDER #START
        Route::get("/team/components/index/{team}", [
            PageBuilderComponentsController::class,
            "index",
        ])->name("components.index");
        // PAGE BUILDER #END
        // PAGE BUILDER #END
        // PAGE BUILDER #END
        // PAGE BUILDER #END
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
    Route::get("/team/posts/post/{teamId}/{post}", [
        PostController::class,
        "edit",
    ])->name("team.posts.post.edit");

    Route::post("/team/posts/post/update/{post}", [
        PostController::class,
        "update",
    ])->name("team.posts.update");

    Route::get("/team/posts/create/{teamId}", [
        PostController::class,
        "create",
    ])->name("team.posts.create");

    Route::post("/team/posts/post/store", [
        PostController::class,
        "store",
    ])->name("team.posts.store");

    Route::post("/team/posts/post/duplicate", [
        PostController::class,
        "duplicate",
    ])->name("team.posts.duplicate");
    // POSTS #END
    // POSTS #END
    // POSTS #END
    // POSTS #END

    // JOBS #START
    // JOBS #START
    // JOBS #START
    // JOBS #START
    Route::get("/team/jobs/job/{teamId}/{job}", [
        JobController::class,
        "edit",
    ])->name("team.jobs.job.edit");

    Route::post("/team/jobs/job/update/{job}", [
        JobController::class,
        "update",
    ])->name("team.jobs.update");

    Route::get("/team/jobs/create/{teamId}", [
        JobController::class,
        "create",
    ])->name("team.jobs.create");

    Route::post("/team/jobs/job/store", [JobController::class, "store"])->name(
        "team.jobs.store"
    );
    Route::post("/team/jobs/jobs/duplicate", [
        JobController::class,
        "duplicate",
    ])->name("team.jobs.duplicate");
    // JOBS #END
    // JOBS #END
    // JOBS #END
    // JOBS #END

    // STORES #START
    // STORES #START
    // STORES #START
    // STORES #START

    Route::get("/team/stores/store/{teamId}/{store}", [
        StoreController::class,
        "edit",
    ])->name("team.stores.store.edit");

    Route::post("/team/stores/store/update/{store}", [
        StoreController::class,
        "update",
    ])->name("team.stores.update");

    Route::get("/team/stores/create/{teamId}", [
        StoreController::class,
        "create",
    ])->name("team.stores.create");

    Route::post("/team/stores/store/store", [
        StoreController::class,
        "store",
    ])->name("team.stores.store");

    Route::post("/team/stores/store/duplicate", [
        StoreController::class,
        "duplicate",
    ])->name("team.stores.duplicate");

    // STORE CREATE SUBSCRIPTION
    // STORE CREATE SUBSCRIPTION
    Route::get("/team/stores/create/subscription/{user}/{team}", [
        SubscriptionController::class,
        "create",
    ])->name("team.stores.create.subscription");

    Route::post("/team/stores/store/subscription", [
        SubscriptionController::class,
        "store",
    ])->name("team.stores.store.subscription");
    // STORE CREATE SUBSCRIPTION
    // STORE CREATE SUBSCRIPTION
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
        Route::get("/users", [UserController::class, "index"])->name(
            "users.index"
        );
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
        Route::get("/stores", [StoreStoreController::class, "index"])->name(
            "stores.index"
        );
        // PROFESSIONAL #END
        // PROFESSIONAL #END
        // PROFESSIONAL #END
        // PROFESSIONAL #END

        // PROFESSIONAL #START
        // PROFESSIONAL #START
        // PROFESSIONAL #START
        // PROFESSIONAL #START
        Route::get("/professional", [
            ProfessionalController::class,
            "index",
        ])->name("professional.index");
        // PROFESSIONAL #END
        // PROFESSIONAL #END
        // PROFESSIONAL #END
        // PROFESSIONAL #END
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

    Route::get("/admin/components/create/{teamId}", [
        PageBuilderController::class,
        "create",
    ])->name("admin.components.component.create");

    Route::get("/admin/components/component/edit/{teamId}/{component}", [
        PageBuilderController::class,
        "edit",
    ])->name("admin.components.component.edit");

    //
    //
    Route::post("/admin/components/component/store", [
        PageBuilderController::class,
        "store",
    ])->name("admin.components.component.store");

    //
    Route::post("/admin/components/component/{componentId}", [
        PageBuilderController::class,
        "update",
    ])->name("admin.components.component.update");

    Route::post("/admin/components/component/duplicate", [
        PageBuilderController::class,
        "duplicate",
    ])->name("admin.components.component.duplicate");

    // destroy
    Route::delete("/admin/components/component/{componentId}/{teamId}", [
        PageBuilderController::class,
        "destroy",
    ])->name("admin.components.component.destroy");
    // ADMIN ONLY #PAGE BUILDER #END
    // ADMIN ONLY #PAGE BUILDER #END
    // ADMIN ONLY #PAGE BUILDER #END
    // ADMIN ONLY #PAGE BUILDER #END

    // ADMIN ONLY #END
    // ADMIN ONLY #END
    // ADMIN ONLY #END
    // ADMIN ONLY #END
});
