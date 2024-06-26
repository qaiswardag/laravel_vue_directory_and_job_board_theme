
# Only run below migrations on a clear Database
php artisan migrate:fresh

# Production Migrations for Launch Date

# Create Teams and Users
php artisan app:teams-users


# Page Builder Migrations
php artisan app:page-builder-component-categories

php artisan app:page-builder-components
php artisan app:page-builder-component-category-relations


# Post Migrations


php artisan app:post-categories

# php artisan app:post
# php artisan app:post-category-relations

# Jobs Migrations


php artisan app:job-categories
php artisan app:job-types
php artisan app:job-states
php artisan app:job-countries


# php artisan app:job
# php artisan app:job-category-relations
# php artisan app:job-type-relations
# php artisan app:job-state-relations
# php artisan app:job-country-relations


# Store Migrations


php artisan app:store-states
php artisan app:store-categories


# php artisan app:store
# php artisan app:store-state-relations
# php artisan app:store-category-relations


