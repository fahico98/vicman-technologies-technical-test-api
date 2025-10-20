<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar tus ajustes para el intercambio de recursos de
    | origen cruzado o "CORS". Esto determina qué operaciones de origen
    | cruzado pueden ejecutarse en los navegadores web.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['precognition', 'precognition-success'],

    'max_age' => 0,

    'supports_credentials' => true
];
