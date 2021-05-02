<?php

return [
    'api_url' => env('IMMANUEL_API_URL', 'https://api.immanuel.app'),
    'api_token' => env('IMMANUEL_API_TOKEN', ''),
    'cache_lifetime' => env('IMMANUEL_CACHE_LIFETIME', 60 * 60 * 24 * 180),
];