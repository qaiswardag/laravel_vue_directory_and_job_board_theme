<?php

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Api\Internal\Guest\Features\JobsGuestIndexController;
use App\Http\Controllers\Api\Internal\Guest\Features\PostsGuestIndexController;
use App\Http\Controllers\Api\Internal\Guest\Features\StoresGuestIndexController;
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
use App\Http\Controllers\Api\Internal\LoggedIn\Stripe\PaymentMethodsController;
use App\Http\Controllers\Api\Internal\LoggedIn\Stripe\PaymentsController;
use App\Http\Controllers\Api\Internal\LoggedIn\Stripe\SubscriptionController as StripeSubscriptionController;
use App\Http\Controllers\Api\Internal\LoggedIn\Teams\AttachTeamsController;
use App\Http\Controllers\Api\Internal\LoggedIn\Teams\SuperadminAttachTeamsController;
use App\Http\Controllers\Api\Internal\LoggedIn\User\MediaLibraryUserController as UserMediaLibraryUserController;
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
use App\Http\Controllers\LoggedIn\Job\JobDeletedController;
use App\Http\Controllers\LoggedIn\Job\JobExpiredController;
use App\Http\Controllers\LoggedIn\MediaLibrary\MediaLibraryController;
use App\Http\Controllers\LoggedIn\Post\PostDeletedController;
use App\Http\Controllers\LoggedIn\Post\PostDraftController;
use App\Http\Controllers\LoggedIn\Store\StoreController;
use App\Http\Controllers\LoggedIn\Store\StoreDeletedController;
use App\Http\Controllers\LoggedIn\Team\TeamController as TeamTeamController;
use App\Http\Controllers\LoggedIn\User\PaymentMethodsController as UserPaymentMethodsController;
use App\Http\Controllers\LoggedIn\User\PaymentsController as UserPaymentsController;
use App\Http\Controllers\LoggedIn\User\SingleChargeJobController;
use App\Http\Controllers\LoggedIn\User\SubscriptionController;
use App\Http\Controllers\LoggedIn\User\UserSessionsController;
use App\Http\Controllers\MediaLibraryUserController;
use App\Http\Controllers\Superadmin\PageBuilder\PageBuilderController;
use App\Http\Controllers\Superadmin\SuperadminManageRoles;
use App\Http\Controllers\Superadmin\SuperadminSwitchTeamController;
use App\Http\Middleware\isSuperAdmin;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use App\Http\Controllers\Api\Internal\LoggedIn\User\LoggedInUser;

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

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/", function () {
        return Inertia::render("Dashboard/Dashboard");
    })->name("home");
    //
});

//
// users
Route::get("/loggedin-user", [LoggedInUser::class, "show"]);
//
// GUESTS ONLY # START
Route::middleware([])->group(function () {
    // USERS #START

    // USERS #END

    // STORES #START
    // CREATE NEW TEAM # START
    if (Jetstream::hasTeamFeatures()) {
        Route::get("/teams/team/create", [
            TeamTeamController::class,
            "create",
        ])->name("teams.create.team");

        Route::post("/teams/store/team", [
            TeamTeamController::class,
            "store",
        ])->name("teams.store.team");
    }
    // CREATE NEW TEAM # END
});

// GUESTS ONLY # END
// AUTH ONLY # START
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/stripe/api/internal/payment/methods/{user}", [
        PaymentMethodsController::class,
        "index",
    ])->name("stripe.api.internal.payment.methods.index");
    //

    Route::post("/stripe/payment/methods/store", [
        UserPaymentMethodsController::class,
        "store",
    ])->name("stripe.payment.methods.store");

    Route::post("/stripe/payment/methods/store/if/single", [
        UserPaymentMethodsController::class,
        "updateIfSinglePatmentMethod",
    ])->name("stripe.payment.methods.store.if.single");

    Route::post("/stripe/payment/methods/update", [
        UserPaymentMethodsController::class,
        "update",
    ])->name("stripe.payment.methods.update");

    Route::delete("/stripe/payment/methods/destroy", [
        UserPaymentMethodsController::class,
        "destroy",
    ])->name("stripe.payment.methods.destroy");

    Route::get("/stripe/stores/edit/subscription/{subscriptionId}", [
        SubscriptionController::class,
        "edit",
    ])->name("stripe.stores.edit.subscription");

    Route::post("/stripe/stores/edit/subscription/{subscriptionId}", [
        SubscriptionController::class,
        "update",
    ])->name("stripe.stores.update.subscription");

    Route::post("/stripe/stores/resume/subscription/{subscriptionId}", [
        SubscriptionController::class,
        "resume",
    ])->name("stripe.stores.resume.subscription");

    Route::delete("/stripe/stores/destroy/subscription/{subscriptionId}", [
        SubscriptionController::class,
        "destroy",
    ])->name("stripe.stores.destroy.subscription");

    Route::get("/stripe/stores/create/subscription", [
        SubscriptionController::class,
        "create",
    ])->name("stripe.stores.create.subscription");

    Route::post("/stripe/stores/store/subscription", [
        SubscriptionController::class,
        "store",
    ])->name("stripe.stores.store.subscription");

    Route::get("/user/subscriptions", [
        SubscriptionController::class,
        "index",
    ])->name("stripe.payment.subscription.index");

    Route::get("/user/payments", [
        UserPaymentsController::class,
        "index",
    ])->name("stripe.payments.index");
    //
    //
    Route::get("/stripe/api/internal/payments/index", [
        PaymentsController::class,
        "index",
    ])->name("stripe.api.internal.payment.index");
    //
    //
    Route::get("/stripe/api/payment/subscriptions/index", [
        StripeSubscriptionController::class,
        "index",
    ])->name("stripe.api.internal.subscriptions.index");

    // Fetch All Users Teams # start
    Route::get("/user/api/internal/teams/index", [
        AttachTeamsController::class,
        "index",
    ])->name("user.api.internal.teams.index");
    // Fetch All Users Teams # end

    // single charge
    Route::get("/single-charge/create/{job}", [
        SingleChargeJobController::class,
        "create",
    ])->name("stripe.single.charge.job.create");

    Route::post("/stripe/single-charge/store/{job}", [
        SingleChargeJobController::class,
        "store",
    ])->name("stripe.single.charge.job.store");

    // Auth only media library # start
    // Auth only media library # start

    Route::get("/media/user/internal/api/index", [
        UserMediaLibraryUserController::class,
        "index",
    ])->name("user.media.index");

    Route::post("/media/user/store", [
        MediaLibraryUserController::class,
        "store",
    ])->name("user.media.store");

    Route::get("/media/user/internal/api/edit/{mediaLibraryUser}", [
        UserMediaLibraryUserController::class,
        "edit",
    ])->name("media.user.edit");

    Route::post("/media/user/image/update", [
        MediaLibraryUserController::class,
        "update",
    ])->name("media.user.update");

    Route::post("/media/user/image/destroy", [
        MediaLibraryUserController::class,
        "destroy",
    ])->name("media.user.destroy");

    // Auth only media library # end

    // PAGE BUILDER #START
    Route::get("/components/index", [
        PageBuilderComponentsController::class,
        "index",
    ])->name("components.index");
    // PAGE BUILDER #END
});
// AUTH ONLY # END

// AUTH & THIER TEAM -> CAN READ ONLY # START
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.can.read", // reader, editor, owner, administrator
])->group(function () {
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

    // POSTS #START
    Route::get("/team/posts/{teamId}", [PostController::class, "index"])->name(
        "team.posts.index"
    );

    Route::get("/team/posts/draft/{teamId}", [
        PostDraftController::class,
        "index",
    ])->name("team.posts.index.draft");
    Route::get("/team/posts/trash/{teamId}", [
        PostDeletedController::class,
        "index",
    ])->name("team.posts.index.trash");
    // unique post
    Route::get("/team/{teamId}/posts/{slug}/view{postId}/", [
        PostController::class,
        "show",
    ])->name("team.posts.post.show");
    // POSTS #END

    // JOBS #START
    Route::get("/team/jobs/{teamId}", [JobController::class, "index"])->name(
        "team.jobs.index"
    );
    Route::get("/team/jobs/draft/{teamId}", [
        JobController::class,
        "IndexDraft",
    ])->name("team.jobs.index.draft");
    Route::get("/team/jobs/expired/{teamId}", [
        JobExpiredController::class,
        "index",
    ])->name("team.jobs.index.expired");
    Route::get("/team/jobs/trash/{teamId}", [
        JobDeletedController::class,
        "index",
    ])->name("team.jobs.index.trash");
    // unique job
    Route::get("/team/{teamId}/jobs/{slug}/view/{jobId}/", [
        JobController::class,
        "show",
    ])->name("team.jobs.job.show");
    // JOBS #END

    // STORE #START
    Route::get("/team/listings/{teamId}", [
        StoreController::class,
        "index",
    ])->name("team.stores.index");

    Route::get("/team/listings/draft/{teamId}", [
        StoreController::class,
        "IndexDraft",
    ])->name("team.stores.index.draft");

    Route::get("/team/listings/trash/{teamId}", [
        StoreDeletedController::class,
        "index",
    ])->name("team.stores.index.trash");

    // unique store
    Route::get("/team/{teamId}/listings/{slug}/view/{storeId}", [
        StoreController::class,
        "show",
    ])->name("team.stores.store.show");
    // STORE #END

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

    // ATTACH USER, POST CATEGORIES, POST TYPES, JOB CATEGORIES, STORE CATEGORIES #START

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
    Route::get("/team/attach/posts/categories/index/{team}", [
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
});

// AUTH & THIER TEAM -> CAN READ ONLY # END

// AUTH & THIER TEAM -> CAN CREATE & UPDATE ONLY # START
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.can.create.and.update", // editor, owner, administrator
])->group(function () {
    //
    // POSTS #START
    Route::get("/team/posts/sale/{teamId}/{post}", [
        PostController::class,
        "edit",
    ])->name("team.posts.post.edit");

    Route::post("/team/posts/sale/update/{post}", [
        PostController::class,
        "update",
    ])->name("team.posts.update");

    Route::get("/team/posts/create/{teamId}", [
        PostController::class,
        "create",
    ])->name("team.posts.create");

    Route::post("/team/posts/sale/store", [
        PostController::class,
        "store",
    ])->name("team.posts.store");

    Route::post("/team/posts/sale/restore/{postId}/{team}", [
        PostDeletedController::class,
        "restore",
    ])->name("team.posts.restore");

    Route::post("/team/posts/duplicate", [
        PostController::class,
        "duplicate",
    ])->name("team.posts.duplicate");
    // POSTS #END

    // JOBS #START
    Route::get("/team/jobs/job/{teamId}/{job}", [
        JobController::class,
        "edit",
    ])->name("team.jobs.job.edit");

    Route::get("/team/jobs/job/republish/{teamId}/{job}", [
        JobExpiredController::class,
        "republish",
    ])->name("team.jobs.job.republish");

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

    Route::post("/team/jobs/job/restore/{jobId}/{team}", [
        JobDeletedController::class,
        "restore",
    ])->name("team.jobs.restore");

    Route::post("/team/jobs/duplicate", [
        JobController::class,
        "duplicate",
    ])->name("team.jobs.duplicate");
    // JOBS #END

    // STORES #START
    Route::get("/team/listings/listing/{teamId}/{store}", [
        StoreController::class,
        "edit",
    ])->name("team.stores.store.edit");

    Route::post("/team/stores/store/update/{store}", [
        StoreController::class,
        "update",
    ])->name("team.stores.update");

    Route::get("/team/listings/create/{teamId}", [
        StoreController::class,
        "create",
    ])->name("team.stores.create");

    Route::post("/team/stores/store/store", [
        StoreController::class,
        "store",
    ])->name("team.stores.store");

    Route::post("/team/stores/store/restore/{storeId}/{team}", [
        StoreDeletedController::class,
        "restore",
    ])->name("team.stores.restore");

    Route::post("/team/stores/duplicate", [
        StoreController::class,
        "duplicate",
    ])->name("team.stores.duplicate");

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
});
// AUTH & THIER TEAM -> CAN CREATE & UPDATE ONLY # END

// AUTH & THIER TEAM -> CAN DESTROY ONLY # START
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    // "ensure.can.destroy", // owner, administrator
])->group(function () {
    // POSTS #START
    Route::delete("/team/posts/sale/{post}/{team}", [
        PostController::class,
        "destroy",
    ])->name("team.posts.post.destroy");

    Route::delete("/team/posts/trash/{post}/{team}", [
        PostDeletedController::class,
        "destroy",
    ])->name("team.posts.post.destroy.force");
    // POSTS #END

    // JOBS #START
    Route::delete("/team/jobs/job/{job}/{team}", [
        JobController::class,
        "destroy",
    ])->name("team.jobs.job.destroy");

    Route::delete("/team/jobs/trash/{job}/{team}", [
        JobDeletedController::class,
        "destroy",
    ])->name("team.jobs.job.destroy.force");
    // JOBS #END

    // STORES #START
    Route::delete("/team/stores/store/{store}/{team}", [
        StoreController::class,
        "destroy",
    ])->name("team.stores.store.destroy");

    Route::delete("/team/stores/trash/{post}/{team}", [
        StoreDeletedController::class,
        "destroy",
    ])->name("team.stores.store.destroy.force");
    // STORES #END

    // MEDIA #START
    Route::post("/team/media/image/destroy/{team}", [
        MediaLibraryController::class,
        "destroy",
    ])->name("media.destroy");
    // MEDIA #END
});
// AUTH & THIER TEAM -> CAN DESTROY ONLY # END

// ADMIN ONLY -> # START
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/admin/dashboard", [DashboardController::class, "index"])->name(
        "admin.dashboard"
    );

    // ADMIN FETCH ALL USERS # START
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
    // ADMIN FETCH ALL USERS # END

    // ADMIN FETCH ALL TEAMS # START
    // Fetch all Teams # start
    Route::get("/superadmin/api/internal/teams/index", [
        SuperadminAttachTeamsController::class,
        "index",
    ])->name("superadmin.api.internal.teams.index");

    Route::put("/superadmin/switch/team", [
        SuperadminSwitchTeamController::class,
        "update",
    ])->name("superadmin.switch.team");
    // Fetch all Teams # end
    // ADMIN FETCH ALL TEAMS # END

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
    Route::post("/admin/components/component/store", [
        PageBuilderController::class,
        "store",
    ])->name("admin.components.component.store");

    //
    Route::post("/admin/components/component/{componentId}", [
        PageBuilderController::class,
        "update",
    ])->name("admin.components.component.update");

    Route::post("/admin/components/duplicate", [
        PageBuilderController::class,
        "duplicate",
    ])->name("admin.components.component.duplicate");

    // destroy
    Route::delete("/admin/components/component/{componentId}/{teamId}", [
        PageBuilderController::class,
        "destroy",
    ])->name("admin.components.component.destroy");

    // ADMIN ONLY #PAGE BUILDER # END
});
// ADMIN ONLY -> # END
