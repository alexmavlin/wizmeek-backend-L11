<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'api-login', 'api-register', 'api-logout'], // pastikan API routes termasuk

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://app.wizmeek.com',      // frontend production
        'https://dev-app.wizmeek.com',  // frontend dev (opsional)
        'http://localhost:3000',        // frontend local dev
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,  // jika memakai cookies / auth
];
