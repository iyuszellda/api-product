<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://www.yustinusmargiyuna.cc', 'https://yustinusmargiyuna.netlify.app', 'http://localhost:5173'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'allow_credentials' => true,
    'max_age' => 0,
    'supports_credentials' => true,
];