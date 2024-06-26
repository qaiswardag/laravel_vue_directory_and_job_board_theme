<?php

namespace App\Http\Controllers\LoggedIn\Store;

use App\Actions\LoggedIn\Stripe\CreateOrGetStripeCustomer;
use App\Actions\LoggedIn\Stripe\TeamHasActiveSubscriptions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\LoggedIn\Store\StoreStoreRequest;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Store\AuthorStore;
use App\Models\Store\Store;
use App\Models\Store\StoreBrandLogoRelation;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreCategoryRelation;
use App\Models\Store\StoreCoverImageRelation;
use App\Models\Store\StoreState;
use App\Models\Store\StoreStateRelation;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        $teamId,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions
    ) {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        // Authorize the team that the user has selected to store the store for, rather than the team that the user is currently on.
        $this->authorize("can-read", $team);

        $stores = $team
            ->stores()
            ->with("coverImages")
            ->with("states")
            ->with("categories")
            ->with("authors")
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->where(function ($query) {
                $query->where("published", true);
            })
            ->orderBy("updated_at", "desc")
            ->paginate(12);

        $stores->appends($request->all());

        // Store created by
        // Retrieve user information for each store
        foreach ($stores as $store) {
            $user = User::find($store->user_id);
            if ($user !== null) {
                $store->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "job_title" => $user->job_title,
                    "profile_photo_path" => $user->profile_photo_path,
                    "id" => $user->id,
                    "username" => $user->username,
                ];
            }
            if ($user === null) {
                $store->updatedBy = null;
            }
        }

        // Check if the number of published stores
        $numberOfPublishedStores = Store::where("team_id", $team->id)
            ->where("published", true)
            ->count();

        return Inertia::render("Stores/Index", [
            "numberOfPublishedStores" => $numberOfPublishedStores,
            "activeSubscriptions" => $teamHasActiveSubscriptions->show($team),
            "posts" => $stores,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ]);
    }

    public function IndexDraft(
        Request $request,
        $teamId,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions
    ) {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        // Authorize the team that the user has selected to store the store for, rather than the team that the user is currently on.
        $this->authorize("can-read", $team);

        $stores = $team
            ->stores()
            ->with("coverImages")
            ->with("states")
            ->with("categories")
            ->with("authors")
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where("title", "like", "%" . $searchQuery . "%")
                    ->orWhere("content", "like", "%" . $searchQuery . "%");
            })
            ->where(function ($query) {
                $query->where("published", false)->orWhereNull("published");
            })
            ->orderBy("updated_at", "desc")
            ->paginate(12);

        $stores->appends($request->all());

        // Store created by
        // Retrieve user information for each store
        foreach ($stores as $store) {
            $user = User::find($store->user_id);
            if ($user !== null) {
                $store->updatedBy = [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "job_title" => $user->job_title,
                    "profile_photo_path" => $user->profile_photo_path,
                    "id" => $user->id,
                    "username" => $user->username,
                ];
            }
            if ($user === null) {
                $store->updatedBy = null;
            }
        }

        // Check if the number of published stores
        $numberOfPublishedStores = Store::where("team_id", $team->id)
            ->where("published", true)
            ->count();

        return Inertia::render("Stores/IndexDraft", [
            "numberOfPublishedStores" => $numberOfPublishedStores,
            "activeSubscriptions" => $teamHasActiveSubscriptions->show($team),
            "posts" => $stores,
            "oldInput" => [
                "search_query" => $request->input("search_query"),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        $teamId,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions,
        CreateOrGetStripeCustomer $createOrGetStripeCustomer
    ) {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        $user = Auth::user();

        // Logic for subscription quantity # start
        if (!$user->superadmin) {
            if ($teamHasActiveSubscriptions->show($team) === 0) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render(
                        "Stripe/CreateStoreSubscription/CreateStoreSubscription",
                        [
                            "intent" => $stripeUserDetails["intent"],
                            "paymentMethods" =>
                                $stripeUserDetails["paymentMethods"],
                            "publishableKey" =>
                                $stripeUserDetails["publishableKey"],
                        ]
                    );
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }

            // Number of published stores is bigger than subscription quantity # start.        $numberOfPublishedStores = Store::where("team_id", $team->id)
            $numberOfPublishedStores = Store::where("team_id", $team->id)
                ->where("published", true)
                ->count();

            if (
                $teamHasActiveSubscriptions->show($team) <=
                $numberOfPublishedStores
            ) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render("Profile/Subscriptions/Index");
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }
        }
        // Number of published stores is bigger than subscription quantity # end.
        // Logic for subscription quantity # end

        return Inertia::render("Stores/CreateStore/CreateStore");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreStoreRequest $request,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions,
        CreateOrGetStripeCustomer $createOrGetStripeCustomer
    ) {
        // Find the current team that the user is on, rather than the team that the user is storing the job for.
        $team = Team::findOrFail($request->team["id"]);

        $this->authorize("can-create-and-update", $team);

        $user = Auth::user();

        // Logic for subscription quantity # start
        if (!$user->superadmin) {
            if ($teamHasActiveSubscriptions->show($team) === 0) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render(
                        "Stripe/CreateStoreSubscription/CreateStoreSubscription",
                        [
                            "intent" => $stripeUserDetails["intent"],
                            "paymentMethods" =>
                                $stripeUserDetails["paymentMethods"],
                            "publishableKey" =>
                                $stripeUserDetails["publishableKey"],
                        ]
                    );
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }

            // Number of published stores is bigger than subscription quantity # start.        $numberOfPublishedStores = Store::where("team_id", $team->id)
            $numberOfPublishedStores = Store::where("team_id", $team->id)
                ->where("published", true)
                ->count();

            if (
                $teamHasActiveSubscriptions->show($team) <=
                $numberOfPublishedStores
            ) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render("Profile/Subscriptions/Index");
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }
            // Number of published stores is bigger than subscription quantity # end.
        }
        // Logic for subscription quantity # end

        $title = $request->title;
        $brandWebsiteUrl = $request->brand_website_url;
        $address = $request->address;
        $contactPageUrl = $request->contact_page_url;
        $floor = $request->floor;
        $content = $request->content;
        $userId = $request->user_id;

        // slug
        $slug = Str::lower(Str::slug($request->slug, "_"));

        // Create the store and store it in a variable
        $store = Store::create([
            "user_id" => $userId,
            "team_id" => $team->id,

            "title" => $title,
            "slug" => $slug,

            "use_team_opening_hours" => $request->use_team_opening_hours,
            "use_store_opening_hours" => $request->use_store_opening_hours,

            "brand_website_url" => $brandWebsiteUrl,
            "address" => $address,
            "floor" => $floor,
            "contact_page_url" => $contactPageUrl,
            "published" => $request->published,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        if ($request->use_store_opening_hours) {
            $store->update([
                "monday_opening_time" => $request->monday_opening_time_store,
                "monday_closing_time" => $request->monday_closing_time_store,

                "tuesday_opening_time" => $request->tuesday_opening_time_store,
                "tuesday_closing_time" => $request->tuesday_closing_time_store,

                "wednesday_opening_time" =>
                    $request->wednesday_opening_time_store,
                "wednesday_closing_time" =>
                    $request->wednesday_closing_time_store,

                "thursday_opening_time" =>
                    $request->thursday_opening_time_store,
                "thursday_closing_time" =>
                    $request->thursday_closing_time_store,

                "friday_opening_time" => $request->friday_opening_time_store,
                "friday_closing_time" => $request->friday_closing_time_store,

                "saturday_opening_time" =>
                    $request->saturday_opening_time_store,
                "saturday_closing_time" =>
                    $request->saturday_closing_time_store,

                "sunday_opening_time" => $request->sunday_opening_time_store,
                "sunday_closing_time" => $request->sunday_closing_time_store,
                "time_zone" => $request->time_zone ?? "Asia/Dubai",
            ]);
        }

        // set team address if team address is null
        if (!is_null($address)) {
            $team->update([
                "address" => $address,
            ]);
        }

        // set team contact page url if null
        if (!is_null($contactPageUrl)) {
            $team->update([
                "contact_page_url" => $contactPageUrl,
            ]);
        }

        if ($request->use_team_opening_hours) {
            $team->update([
                "monday_opening_time" => $request->monday_opening_time_team,
                "monday_closing_time" => $request->monday_closing_time_team,

                "tuesday_opening_time" => $request->tuesday_opening_time_team,
                "tuesday_closing_time" => $request->tuesday_closing_time_team,

                "wednesday_opening_time" =>
                    $request->wednesday_opening_time_team,
                "wednesday_closing_time" =>
                    $request->wednesday_closing_time_team,

                "thursday_opening_time" => $request->thursday_opening_time_team,
                "thursday_closing_time" => $request->thursday_closing_time_team,

                "friday_opening_time" => $request->friday_opening_time_team,
                "friday_closing_time" => $request->friday_closing_time_team,

                "saturday_opening_time" => $request->saturday_opening_time_team,
                "saturday_closing_time" => $request->saturday_closing_time_team,

                "sunday_opening_time" => $request->sunday_opening_time_team,
                "sunday_closing_time" => $request->sunday_closing_time_team,
                "time_zone" => $request->time_zone ?? "Asia/Dubai",
            ]);
        }

        // Get the store ID
        $storeId = $store->id;

        // Authors
        // Check if the "show_author" property of the $request object is true
        // and the "author" property of the $request object is not null
        if (
            $request->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Loop through the authors array and attach each author to the job
            foreach ($request->author as $author) {
                $authorId = $author["id"];

                // Check if a user record with this ID exists
                $userModel = User::find($authorId);

                if ($userModel) {
                    // Create a new record in the author_store table
                    AuthorStore::create([
                        "user_id" => $authorId,
                        "store_id" => $storeId,
                    ]);
                }
            }
        }

        // cover images
        if (
            $request->cover_image !== null &&
            is_array($request->cover_image) &&
            count($request->cover_image) !== 0
        ) {
            // Loop through the array and attach each item to the store
            foreach ($request->cover_image as $image) {
                // Check if the "id" key exists in the $image array
                if (array_key_exists("id", $image)) {
                    $imageId = $image["id"];

                    // Check if a media library record with this ID exists
                    $mediaLibrary = MediaLibrary::find($imageId);
                    if ($mediaLibrary) {
                        // Determine the primary status from the pivot
                        $isPrimary = isset($image["pivot"]["primary"])
                            ? $image["pivot"]["primary"]
                            : false;

                        // Create a new record in the table
                        StoreCoverImageRelation::create([
                            "media_library_id" => $imageId,
                            "store_id" => $storeId,
                            "primary" => $isPrimary,
                        ]);
                    }
                }
            }
        }

        // brand logos
        if (
            $request->brand_logo !== null &&
            is_array($request->brand_logo) &&
            count($request->brand_logo) !== 0
        ) {
            // Loop through the array and attach each item to the store
            foreach ($request->brand_logo as $image) {
                // Check if the "id" key exists in the $image array
                if (array_key_exists("id", $image)) {
                    $imageId = $image["id"];

                    // Check if a media library record with this ID exists
                    $mediaLibrary = MediaLibrary::find($imageId);
                    if ($mediaLibrary) {
                        // Determine the primary status from the pivot
                        $isPrimary = isset($image["pivot"]["primary"])
                            ? $image["pivot"]["primary"]
                            : false;

                        // Create a new record in the table
                        StoreBrandLogoRelation::create([
                            "media_library_id" => $imageId,
                            "store_id" => $storeId,
                            "primary" => $isPrimary,
                        ]);
                    }
                }
            }
        }

        // states
        if (
            $request->states !== null &&
            gettype($request->states) === "array" &&
            count($request->states) !== 0
        ) {
            // Loop through the states array and attach each state to the store
            foreach ($request->states as $state) {
                $stateId = $state["id"];

                // Check if a state record with this ID exists
                $statesModel = StoreState::find($stateId);

                if ($statesModel) {
                    // Create a new record in the table
                    StoreStateRelation::create([
                        "state_id" => $stateId,
                        "store_id" => $storeId,
                    ]);
                }
            }
        }

        // categories
        if (
            $request->categories !== null &&
            is_array($request->categories) &&
            count($request->categories) !== 0
        ) {
            // Loop through the categories array and attach each category to the store
            foreach ($request->categories as $category) {
                // Check if the "id" key exists in the $category array
                if (array_key_exists("id", $category)) {
                    $categoryId = $category["id"];

                    // Check if a category record with this ID exists
                    $categoryModel = StoreCategory::find($categoryId);

                    if ($categoryModel) {
                        // Create a new record in the table
                        StoreCategoryRelation::create([
                            "category_id" => $categoryId,
                            "store_id" => $storeId,
                        ]);
                    }
                }
            }
        }

        if (!$request->published) {
            return redirect()->route("team.stores.index.draft", [
                "teamId" => $team->id,
            ]);
        }
        if ($request->published) {
            return redirect()->route("team.stores.index", [
                "teamId" => $team->id,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store\Store $store
     * @return \Illuminate\Http\Response
     */
    public function show($teamId, $slug, $storeId)
    {
        $storeRenderView = "Stores/Show/ShowTeamStore";

        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        // Retrieve the post, including soft-deleted posts
        $store = Store::withTrashed()
            ->with("coverImages")
            ->with("brandLogos")
            ->findOrFail($storeId);

        // Retrieve the user associated with the store
        $user = User::find($store->user_id);

        // Update the $store array with updatedBy information
        if ($user !== null) {
            $store->updatedBy = [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "profile_photo_path" => $user->profile_photo_path,
                "id" => $user->id,
                "username" => $user->username,
            ];
        }
        if ($user === null) {
            $store->updatedBy = null;
        }

        $authors = [];

        if ($store->show_author) {
            $authors = $store->authors()->get();
        }

        $categories = $store->categories;
        $states = $store->states;

        $storeTeam = Team::find($store->team_id);

        // Render the store
        return Inertia::render($storeRenderView, [
            "post" => $store,
            "authors" => $authors,
            "states" => $states,
            "categories" => $categories,
            "team" => $storeTeam,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $teamId,
        Store $store,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions,
        CreateOrGetStripeCustomer $createOrGetStripeCustomer
    ) {
        $team = Team::find($teamId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        $this->authorize("can-read", $team);

        $user = Auth::user();

        // Logic for subscription quantity # start
        if (!$user->superadmin) {
            if ($teamHasActiveSubscriptions->show($team) === 0) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render(
                        "Stripe/CreateStoreSubscription/CreateStoreSubscription",
                        [
                            "intent" => $stripeUserDetails["intent"],
                            "paymentMethods" =>
                                $stripeUserDetails["paymentMethods"],
                            "publishableKey" =>
                                $stripeUserDetails["publishableKey"],
                        ]
                    );
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }

            // Number of published stores is bigger than subscription quantity # start.        $numberOfPublishedStores = Store::where("team_id", $team->id)
            $numberOfPublishedStores = Store::where("team_id", $team->id)
                ->where("published", true)
                ->count();

            if (
                $teamHasActiveSubscriptions->show($team) <
                $numberOfPublishedStores
            ) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render("Profile/Subscriptions/Index");
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }
            // Number of published stores is bigger than subscription quantity # end.
        }
        // Logic for subscription quantity # end

        $authors = null;

        if ($store->authors !== null) {
            // Fetch related authors
            $relatedAuthors = $store->authors;

            // Loop through related authors and create an array of authors with the required information
            foreach ($relatedAuthors as $user) {
                $authors[] = [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "username" => $user->username,
                    "email" => $user->email,
                    "profile_photo_path" => $user->profile_photo_path,
                    "profile_photo_url" => $user->profile_photo_url,
                ];
            }
        }

        $coverImages = $store->coverImages;
        $brandLogos = $store->brandLogos;
        $categories = $store->categories;
        $states = $store->states;

        return Inertia::render("Stores/UpdateStore/UpdateStore", [
            "post" => $store,
            "postAuthor" => $authors,
            "coverImages" => $coverImages,
            "brandLogos" => $brandLogos,
            "categories" => $categories,
            "states" => $states,
        ]);
    }
    /**
     * Duplicate the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(
        Request $request,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions,
        CreateOrGetStripeCustomer $createOrGetStripeCustomer
    ) {
        $team = Team::findOrFail($request->teamId);
        $store = Store::findOrFail($request->postId);

        if ($team === null) {
            return Inertia::render("Error", [
                "customError" => self::TRY_ANOTHER_ROUTE, // Error message for the user.
                "status" => 404, // HTTP status code for the response.
            ]);
        }

        // Authorize the team that the user has selected
        $this->authorize("can-create-and-update", $team);

        $user = Auth::user();

        // Logic for subscription quantity # start
        if (!$user->superadmin) {
            if ($teamHasActiveSubscriptions->show($team) === 0) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render(
                        "Stripe/CreateStoreSubscription/CreateStoreSubscription",
                        [
                            "intent" => $stripeUserDetails["intent"],
                            "paymentMethods" =>
                                $stripeUserDetails["paymentMethods"],
                            "publishableKey" =>
                                $stripeUserDetails["publishableKey"],
                        ]
                    );
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }

            // Number of published stores is bigger than subscription quantity # start.        $numberOfPublishedStores = Store::where("team_id", $team->id)
            $numberOfPublishedStores = Store::where("team_id", $team->id)
                ->where("published", true)
                ->count();

            if (
                $teamHasActiveSubscriptions->show($team) <=
                $numberOfPublishedStores
            ) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render("Profile/Subscriptions/Index");
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }
            // Number of published stores is bigger than subscription quantity # end.
        }
        // Logic for subscription quantity # end

        $newStore = null;

        try {
            DB::transaction(function () use ($store, &$newStore) {
                // replicate new job # start
                $newStore = $store->replicate();
                $newStore->save();
                $newStore->update([
                    "title" => $newStore->title . " " . "copy",
                    "published" => false,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]);
                // replicate new job # end

                // replicate new categories # start
                if ($store->categories !== null) {
                    foreach ($store->categories as $category) {
                        // Create a new instance of the pivot model
                        $newCategoriesPivotData = new StoreCategoryRelation([
                            "store_id" => $newStore->id,
                            "category_id" => $category->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newCategoriesPivotData->save();
                    }
                }
                // replicate new categories # end

                // replicate new cover images # start
                if ($store->coverImages !== null) {
                    foreach ($store->coverImages as $coverImage) {
                        // Create a new instance of the pivot model
                        $newCoverImagePivotData = new StoreCoverImageRelation([
                            "store_id" => $newStore->id,
                            "media_library_id" => $coverImage->id,
                            "primary" => $coverImage->pivot->primary ?? null,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newCoverImagePivotData->save();
                    }
                }
                // replicate new cover images # start

                // replicate new brand logos # start
                if ($store->brandLogos !== null) {
                    foreach ($store->brandLogos as $brandLogo) {
                        // Create a new instance of the pivot model
                        $newBrandLogoPivotData = new StoreBrandLogoRelation([
                            "store_id" => $newStore->id,
                            "media_library_id" => $brandLogo->id,
                            "primary" => $brandLogo->pivot->primary ?? null,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newBrandLogoPivotData->save();
                    }
                }
                // replicate new brand logos # end

                // replicate new authors  # start
                if ($store->authors !== null) {
                    foreach ($store->authors as $author) {
                        // Create a new instance of the pivot model
                        $newJobAuthorsPivotData = new AuthorStore([
                            "store_id" => $newStore->id,
                            "user_id" => $author->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobAuthorsPivotData->save();
                    }
                }
                // replicate new authors  # end

                // replicate new states # start
                if ($store->states !== null) {
                    foreach ($store->states as $state) {
                        // Create a new instance of the pivot model
                        $newJobStatePivotData = new StoreStateRelation([
                            "store_id" => $newStore->id,
                            "state_id" => $state->id,
                            // Add any other attributes if needed
                        ]);
                        // Save the new pivot data
                        $newJobStatePivotData->save();
                    }
                }
                // replicate new states # end
            });
        } catch (Exception $e) {
            Log::error("Oops! Something went wrong. {$e->getMessage()}.");

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG, // Error message for the user.
                "status" => 422, // HTTP status code for the response.
            ]);
        }

        if ($newStore !== null) {
            return redirect()->route("team.stores.index.draft", [
                "teamId" => $team->id,
            ]);
        }

        return Inertia::render("Error", [
            "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG,
            "status" => 422,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(
        StoreStoreRequest $request,
        Store $store,
        TeamHasActiveSubscriptions $teamHasActiveSubscriptions,
        CreateOrGetStripeCustomer $createOrGetStripeCustomer
    ) {
        // Find the current team that the user is on, rather than the team that the user is storing the store for.
        $team = Team::findOrFail($request->team["id"]);

        $this->authorize("can-create-and-update", $team);

        $user = Auth::user();

        // Logic for subscription quantity # start
        if (!$user->superadmin) {
            if ($teamHasActiveSubscriptions->show($team) === 0) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render(
                        "Stripe/CreateStoreSubscription/CreateStoreSubscription",
                        [
                            "intent" => $stripeUserDetails["intent"],
                            "paymentMethods" =>
                                $stripeUserDetails["paymentMethods"],
                            "publishableKey" =>
                                $stripeUserDetails["publishableKey"],
                        ]
                    );
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }

            // Number of published stores is bigger than subscription quantity # start.        $numberOfPublishedStores = Store::where("team_id", $team->id)
            $numberOfPublishedStores = Store::where("team_id", $team->id)
                ->where("published", true)
                ->count();

            if (
                $teamHasActiveSubscriptions->show($team) <
                $numberOfPublishedStores
            ) {
                try {
                    $stripeUserDetails = $createOrGetStripeCustomer->create();

                    return Inertia::render("Profile/Subscriptions/Index");
                } catch (Exception $e) {
                    return Inertia::render("Error", [
                        "customError" =>
                            self::TRY_CATCH_SOMETHING_WENT_WRONG .
                            " " .
                            $e->getMessage(),
                        "status" => 422,
                    ]);
                }
            }
            // Number of published stores is bigger than subscription quantity # end.
        }
        // Logic for subscription quantity # end

        $slug = $request->slug;
        $title = $request->title;
        $brandWebsiteUrl = $request->brand_website_url;
        $address = $request->address;
        $floor = $request->floor;
        $content = $request->content;
        $teamId = $request->team["id"];
        $userId = $request->user_id;
        $contactPageUrl = $request->contact_page_url;

        // set team address if team address is null
        if (!is_null($address)) {
            $team->update([
                "address" => $address,
            ]);
        }

        // set team opening hours
        if ($request->use_team_opening_hours) {
            $team->update([
                "monday_opening_time" => $request->monday_opening_time_team,
                "monday_closing_time" => $request->monday_closing_time_team,

                "tuesday_opening_time" => $request->tuesday_opening_time_team,
                "tuesday_closing_time" => $request->tuesday_closing_time_team,

                "wednesday_opening_time" =>
                    $request->wednesday_opening_time_team,
                "wednesday_closing_time" =>
                    $request->wednesday_closing_time_team,

                "thursday_opening_time" => $request->thursday_opening_time_team,
                "thursday_closing_time" => $request->thursday_closing_time_team,

                "friday_opening_time" => $request->friday_opening_time_team,
                "friday_closing_time" => $request->friday_closing_time_team,

                "saturday_opening_time" => $request->saturday_opening_time_team,
                "saturday_closing_time" => $request->saturday_closing_time_team,

                "sunday_opening_time" => $request->sunday_opening_time_team,
                "sunday_closing_time" => $request->sunday_closing_time_team,
                "time_zone" => $request->time_zone ?? "Asia/Dubai",
            ]);
        }

        // set team contact page url if null
        if (!is_null($contactPageUrl)) {
            $team->update([
                "contact_page_url" => $contactPageUrl,
            ]);
        }

        // Initialize the $authorId variable to null
        $authorId = null;

        // Create the store and store it in a variable
        $store->update([
            "user_id" => $userId,
            "team_id" => $teamId,
            "title" => $title,
            "slug" => $slug,

            "use_team_opening_hours" => $request->use_team_opening_hours,
            "use_store_opening_hours" => $request->use_store_opening_hours,

            "brand_website_url" => $brandWebsiteUrl,
            "address" => $address,
            "floor" => $floor,
            "contact_page_url" => $contactPageUrl,
            "published" => $request->published,
            "content" => $content,
            "tags" => $request->tags,
            "show_author" => $request->show_author,
        ]);

        if ($request->use_store_opening_hours) {
            $store->update([
                "monday_opening_time" =>
                    $request->monday_opening_time_store === "closed"
                        ? null
                        : $request->monday_opening_time_store,

                "monday_closing_time" =>
                    $request->monday_closing_time_store === "closed"
                        ? null
                        : $request->monday_closing_time_store,

                "tuesday_opening_time" => $request->tuesday_opening_time_store,
                "tuesday_closing_time" => $request->tuesday_closing_time_store,

                "wednesday_opening_time" =>
                    $request->wednesday_opening_time_store,
                "wednesday_closing_time" =>
                    $request->wednesday_closing_time_store,

                "thursday_opening_time" =>
                    $request->thursday_opening_time_store,
                "thursday_closing_time" =>
                    $request->thursday_closing_time_store,

                "friday_opening_time" => $request->friday_opening_time_store,
                "friday_closing_time" => $request->friday_closing_time_store,

                "saturday_opening_time" =>
                    $request->saturday_opening_time_store,
                "saturday_closing_time" =>
                    $request->saturday_closing_time_store,

                "sunday_opening_time" => $request->sunday_opening_time_store,
                "sunday_closing_time" => $request->sunday_closing_time_store,
                "time_zone" => $request->time_zone ?? "Asia/Dubai",
            ]);
        }

        // Get the store ID
        $storeId = $store->id;

        // Update authors
        if (
            $store->show_author === true &&
            $request->author !== null &&
            gettype($request->author) === "array" &&
            count($request->author) !== 0
        ) {
            // Retrieve the existing author IDs for the store
            $existingAuthorIds = AuthorStore::where("store_id", $storeId)
                ->pluck("user_id")
                ->toArray();

            // Loop through the authors array and update or create a record in the author_jobs table
            $updatedAuthorIds = [];
            foreach ($request->author as $author) {
                $authorId = $author["id"];
                $updatedAuthorIds[] = $authorId;

                // Check if a user record with this ID exists
                $userModel = User::find($authorId);

                if ($userModel) {
                    // Update or create the record in the AuthorJob table
                    AuthorStore::updateOrCreate([
                        "user_id" => $authorId,
                        "store_id" => $storeId,
                    ]);
                }
            }

            // Delete the AuthorStore records that are not present in the request
            $authorsToDelete = array_diff(
                $existingAuthorIds,
                $updatedAuthorIds
            );
            AuthorStore::where("store_id", $storeId)
                ->whereIn("user_id", $authorsToDelete)
                ->delete();
        }

        // Update cover images # start
        // Retrieve the existing cover image relationships for the post
        $existingCoverImages = StoreCoverImageRelation::where(
            "store_id",
            $storeId
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingCoverImageIds = $existingCoverImages
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $request->cover_image is not null and is an array
        if (
            $request->cover_image !== null &&
            gettype($request->cover_image) === "array" &&
            count($request->cover_image) !== 0
        ) {
            foreach ($request->cover_image as $coverImage) {
                $imageId = $coverImage["id"];
                $isPrimary = isset($coverImage["pivot"]["primary"])
                    ? $coverImage["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                StoreCoverImageRelation::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "store_id" => $storeId,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingCoverImageIds array
                $key = array_search($imageId, $existingCoverImageIds);
                if ($key !== false) {
                    unset($existingCoverImageIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        StoreCoverImageRelation::where("store_id", $storeId)
            ->whereIn("media_library_id", $existingCoverImageIds)
            ->delete();
        // Update cover images # end

        // Update brand logo # start
        // Retrieve the existing cover image relationships for the post
        $existingBrandLogos = StoreBrandLogoRelation::where(
            "store_id",
            $storeId
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingBrandLogosIds = $existingBrandLogos
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $request->cover_image is not null and is an array
        if (
            $request->brand_logo !== null &&
            gettype($request->brand_logo) === "array" &&
            count($request->brand_logo) !== 0
        ) {
            foreach ($request->brand_logo as $brandLogo) {
                $imageId = $brandLogo["id"];
                $isPrimary = isset($brandLogo["pivot"]["primary"])
                    ? $brandLogo["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                StoreBrandLogoRelation::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "store_id" => $storeId,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingBrandLogosIds array
                $key = array_search($imageId, $existingBrandLogosIds);
                if ($key !== false) {
                    unset($existingBrandLogosIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        StoreBrandLogoRelation::where("store_id", $storeId)
            ->whereIn("media_library_id", $existingBrandLogosIds)
            ->delete();
        // Update brand logo # end

        // Update states
        if (
            $request->states !== null &&
            gettype($request->states) === "array" &&
            count($request->states) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = StoreStateRelation::where(
                "store_id",
                $storeId
            )
                ->pluck("state_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->states as $state) {
                $stateId = $state["id"];
                $updatedResourceIds[] = $stateId;

                // Update or create  record in the table
                StoreStateRelation::updateOrCreate([
                    "state_id" => $stateId,
                    "store_id" => $storeId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            StoreStateRelation::where("store_id", $storeId)
                ->whereIn("state_id", $resourcesToDelete)
                ->delete();
        }

        // Update categories
        if (
            $request->categories !== null &&
            gettype($request->categories) === "array" &&
            count($request->categories) !== 0
        ) {
            // Retrieve the existing resource IDs for the resource
            $existingResourceIds = StoreCategoryRelation::where(
                "store_id",
                $storeId
            )
                ->pluck("category_id")
                ->toArray();

            // Loop through the items array and update or create a record in the table
            $updatedResourceIds = [];

            foreach ($request->categories as $category) {
                $categoryId = $category["id"];
                $updatedResourceIds[] = $categoryId;

                // Update or create  record in the table
                StoreCategoryRelation::updateOrCreate([
                    "category_id" => $categoryId,
                    "store_id" => $storeId,
                ]);
            }

            // Delete records that are not present in the request
            $resourcesToDelete = array_diff(
                $existingResourceIds,
                $updatedResourceIds
            );
            StoreCategoryRelation::where("store_id", $storeId)
                ->whereIn("category_id", $resourcesToDelete)
                ->delete();
        }

        if (!$request->published) {
            return redirect()->route("team.stores.index.draft", [
                "teamId" => $team->id,
            ]);
        }
        if ($request->published) {
            return redirect()->route("team.stores.index", [
                "teamId" => $team->id,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store, Team $team)
    {
        $this->authorize("can-destroy", $team);

        $store->delete();

        return redirect()
            ->back()
            ->with(
                "success",
                "Successfully deleted the Store with id: {$store->id}."
            );
    }
}
