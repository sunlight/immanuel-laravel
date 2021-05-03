<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API URL
    |--------------------------------------------------------------------------
    |
    | This option should not need to be changed unless you are hosting your own
    | version of the API.
    |
    */

    'api_url' => env('IMMANUEL_API_URL', 'https://api.immanuel.app'),

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    |
    | The bearer token that you should receive once signing up for an account
    | at the main https://immanuel.app website.
    |
    */

    'api_token' => env('IMMANUEL_API_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Cache API Responses
    |--------------------------------------------------------------------------
    |
    | Since identical requests will produce identical responses, data received
    | from the API is cached by default. To stop caching, set this to zero.
    |
    */

    'cache' => env('IMMANUEL_CACHE', 1),

    /*
    |--------------------------------------------------------------------------
    | Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Cache lifetime in seconds. To cache API responses indefinitely, set this
    | to zero.
    |
    */

    'cache_lifetime' => env('IMMANUEL_CACHE_LIFETIME', 60 * 60 * 24 * 180),

];