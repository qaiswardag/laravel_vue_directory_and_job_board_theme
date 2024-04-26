<p align="center" dir="auto">
<img width="200" style="max-width: 100%;" src="public/logo/logo.svg" alt="Logo">
</p>

# Intro

Laravel, Vue & Nuxt, a Listing Directory, Blog & Job Board Theme.

## About and demo

This app was born out of my desire to create a minimalist Listing Directory, Blog & Job Board Theme.

[Play around with the demo app](https://www.myissue.io)

# Technologies and Frameworks

Developed with Laravel, Vue 3, Vite, Vuex, Inertia and Tailwind CSS.

# Installation

-   Clone the repository and install PHP dependencies using Composer:

```
composer install
```

-   Install NPM dependencies:

```
npm install
```

-   Build assets:

```
npm run dev
```

-   Setup configuration:

```
cp .env.example .env
```

-   Generate an application key:

```
php artisan key:generate
```

-   Create an database and run the database migrations:

```
php artisan migrate
```

-   Run the database seeder:

```
php artisan db:seed
```

-   Start the project:

```
 php artisan serve
```

-   Additionally, if you want to rebuild your assets during development, you can run the following command separately:

```
 npm run dev
```

## Required Software Installation

Please note that these instructions assume you have PHP, Composer, and Node.js installed

# Documentation

## Inertia.js Documentation

Inertia.js is a powerful JavaScript framework that allows you to build modern, single-page applications using server-side routing and Laravel. By integrating Inertia.js into your Laravel project, you can create a seamless user experience while customizing your admin panel. Dive into the Inertia.js documentation to learn about its features, installation process, and how to integrate it with your Laravel application.

[Inertia.js Documentation](https://inertiajs.com/)

## Laravel Documentation

Laravel is a popular PHP web application framework known for its elegant syntax and developer-friendly features. The Laravel documentation provides comprehensive guidance on building and customizing web applications, including admin panels. Explore the documentation to understand Laravel's core concepts, routing, database interactions, and more. Learning Laravel will empower you to make meaningful customizations to your admin panel.

[Laravel Documentation](https://laravel.com/docs/10.x)

## Laravel Jetstream Documentation

Laravel Jetstream is a beautifully designed application scaffolding for Laravel. It provides pre-built authentication, user management, and more. By leveraging Laravel Jetstream, you can accelerate the development of your admin panel and focus on customizing the features that matter most. The documentation will walk you through the setup and customization of Jetstream, helping you build a robust admin panel efficiently.

[Laravel Jetstream Documentation](https://jetstream.laravel.com/introduction.html)
