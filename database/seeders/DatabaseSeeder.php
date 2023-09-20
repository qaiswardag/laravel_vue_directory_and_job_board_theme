<?php

namespace Database\Seeders;

use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use App\Models\Job\JobCountry;
use App\Models\Job\JobState;
use App\Models\Job\JobType;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use App\Models\Store\Store;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreState;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategory;
use App\Models\Superadmin\Superadmin;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Exception;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Random users

        // Check if the user with ID 1 exists
        if (!User::where("id", 1)->exists()) {
            User::factory()->create([
                "id" => 1,
                "first_name" => "Qais",
                "last_name" => "Wardag",
                "email" => "qw@myself.ae",
                "public" => true,
                "current_team_id" => 1,
                "password" => Hash::make("123456"),
            ]);

            Superadmin::factory()->create([
                "user_id" => 1, // Qais Wardag
                "role" => "admin", // reader, editor, or admin
            ]);
        }

        // Check if the team with ID 1 exists
        if (!Team::where("id", 1)->exists()) {
            Team::factory()->create([
                "id" => 1,
                "user_id" => User::find(1)->id,
                "name" => "myself",
                "public" => true,
                "personal_team" => false,
            ]);
        }

        // Shaun Pelling
        User::factory()->create([
            "id" => 2,
            "first_name" => "Shaun",
            "last_name" => "Pelling",
            "email" => "sp@myself.ae",

            "public" => false,
            "current_team_id" => 2,
            "password" => Hash::make("123456"),
        ]);

        Superadmin::factory()->create([
            "user_id" => 2, // Shaun Pelling
            "role" => "editor", // reader, editor or admin
        ]);

        Team::factory()->create([
            "id" => 2,
            "user_id" => User::find(2)->id,
            "name" => "Lacoste",
            "public" => true,
            "personal_team" => false,
        ]);

        // Mie Mortensen
        User::factory()->create([
            "id" => 3,
            "first_name" => "Mie",
            "last_name" => "Mortensen",
            "email" => "mm@myself.ae",

            "public" => true,
            "current_team_id" => 3,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 3,
            "user_id" => User::find(3)->id,
            "name" => "adidas",
            "public" => true,
            "personal_team" => false,
        ]);
        // Marilyn Monroe
        User::factory()->create([
            "id" => 4,
            "first_name" => "Marilyn",
            "last_name" => "Monroe",
            "email" => "mjm@myself.ae",
            "current_team_id" => 4,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 4,
            "user_id" => User::find(4)->id,
            "name" => "RCA Records",
            "public" => true,
            "personal_team" => false,
        ]);
        // Oprah Winfrey
        User::factory()->create([
            "id" => 5,
            "first_name" => "Oprah",
            "last_name" => "Winfrey",
            "email" => "ow@myself.ae",
            "current_team_id" => 5,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 5,
            "user_id" => User::find(5)->id,
            "name" => "Harpo Productions Oprah",
            "public" => true,
            "personal_team" => false,
        ]);
        // Ingrid Bergman
        User::factory()->create([
            "id" => 6,
            "first_name" => "Ingrid",
            "last_name" => "Bergman",
            "email" => "ib@myself.ae",
            "current_team_id" => 6,
            "password" => Hash::make("123456"),
        ]);

        Team::factory()->create([
            "id" => 6,
            "user_id" => User::find(6)->id,
            "name" => "IBI Interiors",
            "public" => true,
            "personal_team" => false,
        ]);
        //
        // Karl Lagerfeld
        User::factory()->create([
            "id" => 7,
            "first_name" => "Karl",
            "last_name" => "Lagerfeld",
            "email" => "kl@myself.ae",
            "current_team_id" => 1,
            "password" => Hash::make("123456"),
        ]);

        // Users Joining Teams

        // Qais Wardag joining Teams
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id, // Shaun Pelling Team
            "user_id" => User::find(1)->id, // Qais Wardag
            "role" => "admin", // editor
        ]);

        TeamUser::factory()->create([
            "team_id" => Team::find(3)->id, // Mie Mortensens Team
            "user_id" => User::find(1)->id, // Qais Wardag
            "role" => "editor", // editor
        ]);

        // Shaun Pelling joined Mie Mortensens team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Mie Mortensens
            "user_id" => User::find(2)->id, // Shaun Pelling
            "role" => "admin", // editor
        ]);

        // Marilyn, Oprah and Ingrid joined Shaun Pelling team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Shaun Pelling team
            "user_id" => User::find(4)->id, // Marilyn
            "role" => "admin",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Shaun Pelling team
            "user_id" => User::find(5)->id, // Oprah
            "role" => "editor",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Shaun Pelling team
            "user_id" => User::find(6)->id, // Ingrid
            "role" => "editor",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Shaun Pelling
            "user_id" => User::find(7)->id, // Karl Lagerfeld
            "role" => "admin", // editor
        ]);
        // Mie Mortensen joined Qais Wardag team
        TeamUser::factory()->create([
            "team_id" => Team::find(1)->id, // Shaun Pelling
            "user_id" => User::find(3)->id, // Mie Mortensen
            "role" => "reader", // editor
        ]);

        //
        // Pending invitations
        // Users Pending Team Invitations for Shaun Pelling team
        TeamInvitation::factory()->create([
            "team_id" => 1, // Shaun Pelling
            "email" => "join_Johns_team_1@outlook.com",
            "role" => "admin",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 1, // Shaun Pelling
            "email" => "join_shaun_pellings_2@outlook.com",
            "role" => "editor",
        ]);

        // Users Pending Team Invitations for Mie Mortensens team
        TeamInvitation::factory()->create([
            "team_id" => 1, // Mie Mortensen
            "email" => "join_mies_team_1@outlook.com",
            "role" => "admin",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 1, // Mie Mortensen
            "email" => "join_mies_team_2@outlook.com",
            "role" => "editor",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 1, // Mie Mortensen
            "email" => "join_mies_team_3@outlook.com",
            "role" => "admin",
        ]);

        // Create 100 users from id 101 to 201 as user with id 101 is already created
        User::factory(100)->create();

        // add users to Shaun Pellings Team
        foreach (range(20, 100) as $num) {
            $user = User::find($num);

            if ($user !== null) {
                // User exists, proceed with creating team-user relationship
                TeamUser::factory()->create([
                    "team_id" => Team::find(1)->id, // Shaun Pellings team
                    "user_id" => $user->id,
                    "role" => "editor",
                ]);
            }
            if ($user === false) {
                throw new Exception("Unable to add User to John Does Team.");
            }

            if ($num == 50) {
                break;
            }
        }

        // fake resources

        // Post
        // Post
        // Post
        // Post
        Post::factory(20)->create();
        $totalPost = Post::count();

        $postCategories = [
            "Beauty",
            "Business",
            "Careers",
            "Designers",
            "Direct-to-Consumer",
            "Entrepreneurship",
            "Events",
            "Fashion",
            "Fashion Week",
            "Financial Markets",
            "Global Markets",
            "Home",
            "Insights",
            "Jobs",
            "Luxury",
            "Marketing",
            "Media",
            "News",
            "Opinions",
            "People",
            "Podcasts",
            "Retail",
            "Runway",
            "Shop",
            "Sustainability",
            "Technology",
            "The State of Fashion",
            "Trends",
            "UAE",
            "Workplace & Talent",
        ];

        foreach ($postCategories as $categoryName) {
            PostCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->associateCategoriesWithItems(
            Post::class,
            $totalPost,
            rand(2, 4),
            PostCategory::class,
            "categories"
        );

        // Jobs
        // Jobs
        // Jobs
        // Jobs
        Job::factory(20)->create();
        $totalJobs = Job::count();

        $jobCountries = [
            "Other",
            "Bahrain",
            "Kuwait",
            "Qatar",
            "Saudi Arabia",
            "Turkey",
            "United Arab Emirates",
        ];

        foreach ($jobCountries as $jobCountry) {
            JobCountry::factory()->create([
                "name" => $jobCountry,
            ]);
        }

        $this->associateCategoriesWithItems(
            Job::class,
            $totalJobs,
            1,
            JobCountry::class,
            "countries"
        );
        //
        //
        $states = [
            "Other",
            "Abu Dhabi",
            "Dubai",
            "Sharjah",
            "Ajman",
            "Umm Al Quwain",
            "Ras Al Khaimah",
            "Fujairah",
        ];

        foreach ($states as $stateName) {
            JobState::factory()->create([
                "name" => $stateName,
            ]);
        }

        $this->associateCategoriesWithItems(
            Job::class,
            $totalJobs,
            1,
            JobState::class,
            "states"
        );

        //
        //
        $types = [
            "Freelance",
            "Full-time",
            "Hybrid",
            "Internship",
            "Part-time",
            "Remote",
            "Student Job",
            "Temporary",
            "Voluntary",
        ];

        foreach ($types as $typeName) {
            JobType::factory()->create([
                "name" => $typeName,
            ]);
        }
        $this->associateCategoriesWithItems(
            Job::class,
            $totalJobs,
            rand(1, 2),
            JobType::class,
            "types"
        );
        //
        //
        $categories = [
            "Accounting",
            "Administration",
            "Analytics",
            "Architecture",
            "Arts & Creative",
            "Beauty",
            "Blogger",
            "Communications",
            "Coordination",
            "Customer Service",
            "Design",
            "Developer",
            "Digital & E-commerce",
            "Education & Research",
            "Entrepreneur",
            "Events",
            "Finance",
            "Franchise",
            "Graphic Design",
            "HR",
            "Influencer",
            "Investor Relations",
            "IT",
            "Legal",
            "MakeUp & Styling",
            "Management",
            "Marketing",
            "Media & Editorial",
            "Merchandising",
            "Model",
            "Optics",
            "Photography & Photo Editing",
            "PR & Branding",
            "Product Design",
            "Product Management",
            "Production",
            "Project Management",
            "Purchasing",
            "Retail",
            "Sales & Wholesale",
            "Shipping & Logistics",
            "Shopping",
            "SoMe",
            "Stock & Supply",
            "Visual Merchandiser",
            "Web & Online",
        ];

        foreach ($categories as $categoryName) {
            JobCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }
        $this->associateCategoriesWithItems(
            Job::class,
            $totalJobs,
            rand(2, 4),
            JobCategory::class,
            "categories"
        );
        //
        // Stores
        // Stores
        // Stores
        // Stores
        Store::factory(20)->create();
        $totalStores = Store::count();
        $states = [
            "Abu Dhabi",
            "Dubai",
            "Sharjah",
            "Ajman",
            "Umm Al Quwain",
            "Ras Al Khaimah",
            "Fujairah",
        ];

        foreach ($states as $stateName) {
            StoreState::factory()->create([
                "name" => $stateName,
            ]);
        }

        $this->associateCategoriesWithItems(
            Store::class,
            $totalStores,
            1,
            StoreState::class,
            "states"
        );
        //
        //
        $categories = [
            "Accessories",
            "Bags & Leather Goods",
            "Beauty & Skincare",
            "Children's Fashion",
            "Department Stores",
            "Events",
            "Eyewear",
            "Home & Lifestyle",
            "Jewelry",
            "Lingerie & Nightwear",
            "Men's Fashion",
            "Perfumes",
            "Shoes",
            "Sports & Leisure",
            "Tailors",
            "Watches",
            "Wedding & Occasion Wear",
            "Women's Fashion",
        ];

        foreach ($categories as $categoryName) {
            StoreCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->associateCategoriesWithItems(
            Store::class,
            $totalStores,
            rand(2, 4),
            StoreCategory::class,
            "categories"
        );
        //
        //
        // Page Builder
        // Page Builder
        // Page Builder
        // Page Builder
        $imagePath = null;
        $pageBuilderComponents = [
            [
                "user_id" => rand(1, 2),
                "title" => "This one is not published",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 0,
            ],
            [
                "user_id" => rand(1, 2),
                "title" => "Also this one is not published",

                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 0,
            ],
            [
                "user_id" => rand(1, 2),
                "title" => "Hello & Bonjour 3",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
            ],
            [
                "user_id" => rand(1, 2),
                "title" => "Hello & Bonjour 4",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
            ],
            [
                "user_id" => rand(1, 2),
                "title" => "Hello & Bonjour 5",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
            ],
            [
                "user_id" => rand(1, 2),
                "title" => "Hello & Bonjour 6",
                "html_code" =>
                    "<section> <section> <div class=\"py-8 mx-auto max-w-7xl px-6 bg-black\"> <div class=\"grid lg:grid-cols-2 sm:gap-2 gap-2\"> <div> <h2 class=\"text-4xl tracking-tight font-bold sm:text-5xl md:text-6xl mb-6 text-gray-100\"> Hello &amp; Bonjour </h2> </div> <div> <p class=\"text-gray-100\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio</span>.</p> </div> </div> </div> </section> </section>",
                "published" => 1,
            ],
            // Add data for other components here
        ];

        foreach ($pageBuilderComponents as $pageBuildercomponent) {
            PageBuilderComponent::factory()->create($pageBuildercomponent);
        }
        //
        //
        //
        //
        $categories = [
            "Articles & Contents",
            // "Call to Actions",
            // "Contact",
            "Headers",
            "Opening Hours",
            "Products & Sale",
            // "Features",
            // "About Us",
            // "Links & Buttons",
            // "Templates",
            // "Social Media",
            // "Footers",
            "Images",
        ];

        // Sort the categories alphabetically
        sort($categories);

        foreach ($categories as $categoryName) {
            PageBuilderComponentCategory::factory()->create([
                "name" => $categoryName,
            ]);
        }

        $this->associateCategoriesWithItems(
            PageBuilderComponent::class,
            count($pageBuilderComponents),
            rand(2, 4),
            PageBuilderComponentCategory::class,
            "categories"
        );
    }
    //
    //
    //
    //
    //

    private function associateCategoriesWithItems(
        $model,
        $total,
        $numberOfRuns,
        $categoryModel,
        $categoryAttribute
    ) {
        for ($run = 1; $run <= $numberOfRuns; $run++) {
            foreach (range(1, $total) as $num) {
                $item = $model::find($num);

                if ($item !== null) {
                    $randomCategory = $categoryModel
                        ::inRandomOrder()
                        ->whereNotIn(
                            "id",
                            $item->$categoryAttribute->pluck("id")
                        )
                        ->first();

                    if ($randomCategory !== null) {
                        $item
                            ->$categoryAttribute()
                            ->attach($randomCategory->id);
                    }

                    if ($randomCategory === null) {
                        throw new Exception(
                            "Unable to find a unique random category."
                        );
                    }
                }

                if ($item === null) {
                    throw new Exception(
                        "Unable to find the $model with ID $num."
                    );
                }
            }
        }
    }
}
