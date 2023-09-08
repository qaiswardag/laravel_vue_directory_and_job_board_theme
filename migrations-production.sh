
# Only run below migrations on a clear Database
php artisan migrate:fresh

# Migrations for production
php artisan app:page-builder-component-categories

php artisan app:page-builder-components

php artisan app:page-builder-component-category-relations

php artisan app:teams-users

php artisan app:job

php artisan app:job-categorys

php artisan app:job-category-relations

