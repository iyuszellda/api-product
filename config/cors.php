<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '')),
    'allowed_headers' => ['Content-Type', 'X-XSRF-TOKEN', 'Authorization'],
    'allow_credentials' => true,
    'max_age' => 0,
    'supports_credentials' => true,
];
