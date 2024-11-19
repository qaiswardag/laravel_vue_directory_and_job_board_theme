<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    "paths" => ["api/*", "loggedin-user", "sanctum/csrf-cookie"],

    "allowed_methods" => ["*"],

    "allowed_origins" => [
        "https://www.demo-admin.myissue.dk",
        "https://www.demo.myissue.dk",
        "http://localhost:8000",
        "http://localhost:4000",
    ],

    "allowed_origins_patterns" => [],

    "allowed_headers" => ["*"],

    "exposed_headers" => [],

    "max_age" => 0,

    "supports_credentials" => true,
];
