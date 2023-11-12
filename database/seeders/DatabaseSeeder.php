<?php

namespace Database\Seeders;

use App\Models\Job\Job;
use App\Models\Job\JobCategory;
use App\Models\Job\JobCountry;
use App\Models\Job\JobCoverImageRelation;
use App\Models\Job\JobState;
use App\Models\Job\JobType;
use App\Models\MediaLibrary\MediaLibrary;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use App\Models\Post\PostCoverImageRelation;
use App\Models\Store\Store;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreCoverImageRelation;
use App\Models\Store\StoreState;
use App\Models\Superadmin\PageBuilder\PageBuilderComponent;
use App\Models\Superadmin\PageBuilder\PageBuilderComponentCategory;
use App\Models\Superadmin\PageBuilder\PageBuilderCoverImageRelation;
use App\Models\Superadmin\Superadmin;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use App\PageBuilderComponents\PageBuilderComponentsTemplates;
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
                "current_team_id" => 2,
                "password" => Hash::make("123456"),
            ]);

            Superadmin::factory()->create([
                "user_id" => 1,
                "role" => "admin", // reader, editor, or admin
            ]);
        }

        // Check if the team with ID 1 exists
        if (!Team::where("id", 1)->exists()) {
            Team::factory()->create([
                "id" => 1,
                "user_id" => User::find(1)->id,
                "name" => "myself",
                "slug" => "myself",
                "public" => true,
                "personal_team" => false,
            ]);
        }

        // Mie Mortensen
        User::factory()->create([
            "id" => 2,
            "first_name" => "Mie",
            "last_name" => "Mortensen",
            "email" => "mm@myself.ae",

            "public" => true,
            "current_team_id" => 2,
            "password" => Hash::make("123456"),
        ]);

        Superadmin::factory()->create([
            "user_id" => 2,
            "role" => "reader", // reader, editor or admin
        ]);

        Team::factory()->create([
            "id" => 2,
            "user_id" => User::find(2)->id,
            "name" => "Dubai Mall",
            "slug" => "Dubai Mall",
            "public" => true,
            "personal_team" => false,
        ]);

        // Shaun Pelling
        User::factory()->create([
            "id" => 3,
            "first_name" => "Shaun",
            "last_name" => "Pelling",
            "email" => "sp@myself.ae",

            "public" => false,
            "current_team_id" => 3,
            "password" => Hash::make("123456"),
        ]);

        Superadmin::factory()->create([
            "user_id" => 3, // Shaun Pelling
            "role" => "editor", // reader, editor or admin
        ]);

        Team::factory()->create([
            "id" => 3,
            "user_id" => User::find(3)->id,
            "name" => "Calvin Klein",
            "slug" => "Calvin Klein",
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
            "slug" => "Harpo Productions Oprah",
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
            "slug" => "IBI Interiors",
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

        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(1)->id,
            "role" => "admin",
        ]);

        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(3)->id,
            "role" => "editor",
        ]);

        TeamUser::factory()->create([
            "team_id" => Team::find(3)->id,
            "user_id" => User::find(1)->id,
            "role" => "editor", // editor
        ]);

        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(2)->id,
            "role" => "admin", // editor
        ]);

        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(4)->id,
            "role" => "admin",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(5)->id, // Oprah
            "role" => "editor",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(6)->id, // Ingrid
            "role" => "editor",
        ]);
        TeamUser::factory()->create([
            "team_id" => Team::find(2)->id,
            "user_id" => User::find(7)->id, // Karl Lagerfeld
            "role" => "admin",
        ]);

        // Pending invitations
        // Users Pending Team Invitations for Shaun Pelling team
        TeamInvitation::factory()->create([
            "team_id" => 2,
            "email" => "join_Johns_team_2@outlook.com",
            "role" => "admin",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 2,
            "email" => "join_shaun_pellings_2@outlook.com",
            "role" => "editor",
        ]);

        TeamInvitation::factory()->create([
            "team_id" => 2,
            "email" => "join_mies_team_2@outlook.com",
            "role" => "editor",
        ]);
        TeamInvitation::factory()->create([
            "team_id" => 2,
            "email" => "join_mies_team_3@outlook.com",
            "role" => "admin",
        ]);

        // Create 100 users from id 101 to 201 as user with id 101 is already created
        User::factory(100)->create();

        // add users to Shaun Pellings Team
        foreach (range(10, 100) as $num) {
            $user = User::find($num);

            if ($user !== null) {
                // User exists, proceed with creating team-user relationship
                TeamUser::factory()->create([
                    "team_id" => Team::find(2)->id,
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

        $numbersOffakeResources = 800;
        // Post
        // Post
        // Post
        // Post
        Post::factory($numbersOffakeResources)->create();
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
            rand(2, 2),
            PostCategory::class,
            "categories"
        );

        // Jobs
        // Jobs
        // Jobs
        // Jobs
        Job::factory($numbersOffakeResources)->create();
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
            rand(2, 2),
            JobCategory::class,
            "categories"
        );
        //
        // Stores
        // Stores
        // Stores
        // Stores
        Store::factory($numbersOffakeResources)->create();
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
            rand(2, 2),
            StoreCategory::class,
            "categories"
        );
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
        // Page Builder
        // Page Builder
        // Page Builder
        // Page Builder

        // Create an instance of PageBuilderComponentsTemplates
        $pageBuilderTemplates = new PageBuilderComponentsTemplates();

        // Call the index function to get the array
        $pageBuilderComponents = $pageBuilderTemplates->index();

        foreach ($pageBuilderComponents as $pageBuildercomponent) {
            PageBuilderComponent::factory()->create($pageBuildercomponent);
        }

        //
        PageBuilderComponent::factory(200)->create();
        $totalComponents = PageBuilderComponent::count();
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
            $totalComponents,
            2,
            PageBuilderComponentCategory::class,
            "categories"
        );

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
        // fake images for components, posts, jobs & stores # start
        // fake images for components, posts, jobs & stores # start
        //
        //
        //
        // fake images for components # start
        // number of images in fake-images & stores folder

        $directory = storage_path(
            "app/public/uploads/components/single_component_cover_image"
        );
        $files = scandir($directory);

        // Remove unwanted files like . and ..
        $filteredFiles = array_filter($files, function ($file) {
            return !in_array($file, [".", "..", ".DS_Store"]);
        });

        $imagesFileCountComponents = count($filteredFiles);

        foreach ($filteredFiles as $file) {
            $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $path = "components/single_component_cover_image/{$filenameWithoutExtension}.jpg";

            MediaLibrary::factory()->create([
                "user_id" => 1,
                "team_id" => 1,
                "path" => $path,
                "thumbnail_path" => $path,
                "medium_path" => $path,
                "large_path" => $path,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
                "extension" => "jpg",
            ]);
        }
        // fake images for components # start
        for ($number = 1; $number <= PageBuilderComponent::count(); $number++) {
            $randomMediaLibraryId = rand(1, MediaLibrary::count());

            PageBuilderCoverImageRelation::factory()->create([
                "media_library_id" => $randomMediaLibraryId,
                "component_id" => $number,
            ]);
        }

        //
        //
        //
        // fake images for posts # start
        // number of images in fake-images & stores folder

        $directory = storage_path("app/public/uploads/fake-images/posts");
        $files = scandir($directory);

        // Remove unwanted files like . and ..
        $filteredFiles = array_filter($files, function ($file) {
            return !in_array($file, [".", "..", ".DS_Store"]);
        });

        $imagesFileCountPosts = count($filteredFiles);

        foreach ($filteredFiles as $file) {
            $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $path = "fake-images/posts/{$filenameWithoutExtension}.jpg";

            MediaLibrary::factory()->create([
                "user_id" => 1,
                "team_id" => 2,
                "path" => $path,
                "thumbnail_path" => $path,
                "medium_path" => $path,
                "large_path" => $path,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
                "extension" => "jpg",
            ]);
        }
        // fake images for stores # start
        for ($number = 1; $number <= Post::count(); $number++) {
            $randomMediaLibraryId = rand(
                $imagesFileCountComponents + 1,
                MediaLibrary::count()
            );

            PostCoverImageRelation::factory()->create([
                "media_library_id" => $randomMediaLibraryId,
                "post_id" => $number,
                "primary" => 0
            ]);
        }

        // fake images for jobs # start
        // number of images in fake-images & stores folder

        $directory = storage_path("app/public/uploads/fake-images/jobs");
        $files = scandir($directory);

        // Remove unwanted files like . and ..
        $filteredFiles = array_filter($files, function ($file) {
            return !in_array($file, [".", "..", ".DS_Store"]);
        });

        $imagesFileCountJobs = count($filteredFiles);

        foreach ($filteredFiles as $file) {
            $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $path = "fake-images/jobs/{$filenameWithoutExtension}.jpg";

            MediaLibrary::factory()->create([
                "user_id" => 1,
                "team_id" => 2,
                "path" => $path,
                "thumbnail_path" => $path,
                "medium_path" => $path,
                "large_path" => $path,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
                "extension" => "jpg",
            ]);
        }
        // fake images for stores # start
        for ($number = 1; $number <= Job::count(); $number++) {
            $randomMediaLibraryId = rand(
                $imagesFileCountComponents + $imagesFileCountPosts + 1,
                MediaLibrary::count()
            );

            JobCoverImageRelation::factory()->create([
                "media_library_id" => $randomMediaLibraryId,
                "job_id" => $number,
                "primary" => 0
            ]);
        }

        // fake images for stores # start
        // number of images in fake-images & stores folder

        $directory = storage_path("app/public/uploads/fake-images/stores");
        $files = scandir($directory);

        // Remove unwanted files like . and ..
        $filteredFiles = array_filter($files, function ($file) {
            return !in_array($file, [".", "..", ".DS_Store"]);
        });

        $imagesFileCountStores = count($filteredFiles);

        foreach ($filteredFiles as $file) {
            $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $path = "fake-images/stores/{$filenameWithoutExtension}.jpg";

            MediaLibrary::factory()->create([
                "user_id" => 1,
                "team_id" => 2,
                "path" => $path,
                "thumbnail_path" => $path,
                "medium_path" => $path,
                "large_path" => $path,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
                "extension" => "jpg",
            ]);
        }
        // fake images for stores # start
        for ($number = 1; $number <= Store::count(); $number++) {
            $randomMediaLibraryId = rand(
                $imagesFileCountComponents +
                    $imagesFileCountPosts +
                    $imagesFileCountJobs +
                    1,
                MediaLibrary::count()
            );

            StoreCoverImageRelation::factory()->create([
                "media_library_id" => $randomMediaLibraryId,
                "store_id" => $number,
                "primary" => 0
            ]);
        }

        //
        //
        // fake images for components & Elements # start
        // fake images for components & Elements # start
        //
        $directory = storage_path(
            "app/public/uploads/components/component_html_element_images"
        );
        $files = scandir($directory);

        // Remove unwanted files like . and ..
        $filteredFiles = array_filter($files, function ($file) {
            return !in_array($file, [".", "..", ".DS_Store"]);
        });

        $imagesFileCountImagesElements = count($filteredFiles);

        foreach ($filteredFiles as $file) {
            $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $path = "components/component_html_element_images/{$filenameWithoutExtension}.jpg";

            MediaLibrary::factory()->create([
                "user_id" => 1,
                "team_id" => 1,
                "name" => "For HTLM element",
                "path" => $path,
                "thumbnail_path" => $path,
                "medium_path" => $path,
                "large_path" => $path,
                "size" => 1000,
                "width" => 1000,
                "height" => 1000,
                "extension" => "jpg",
            ]);
        }

        //
        // fake HTML images for components & Elements # end
        // fake HTML images for components & Elements # end
        //
        //
    }
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
