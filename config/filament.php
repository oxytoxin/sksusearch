<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Environment
    |--------------------------------------------------------------------------
    |
    | These values preserve the environment overrides supported by the v2
    | configuration while the panel itself is configured by its provider.
    |
    */

    'panel' => [
        'path' => env('FILAMENT_PATH', 'admin'),
        'domain' => env('FILAMENT_DOMAIN'),
        'auth_guard' => env('FILAMENT_AUTH_GUARD', 'web'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    */

    'broadcasting' => [

        // 'echo' => [
        //     'broadcaster' => 'reverb',
        //     'key' => env('VITE_REVERB_APP_KEY'),
        //     'wsHost' => env('VITE_REVERB_HOST'),
        //     'wsPort' => env('VITE_REVERB_PORT'),
        //     'wssPort' => env('VITE_REVERB_PORT'),
        //     'authEndpoint' => '/broadcasting/auth',
        //     'disableStats' => true,
        //     'encrypted' => true,
        //     'forceTLS' => env('VITE_REVERB_SCHEME', 'https') === 'https',
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default_filesystem_disk' => env(
        'FILAMENT_FILESYSTEM_DISK',
        env('FILAMENT_FILESYSTEM_DRIVER', 'public'),
    ),

    /*
    |--------------------------------------------------------------------------
    | Assets Path
    |--------------------------------------------------------------------------
    */

    'assets_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache Path
    |--------------------------------------------------------------------------
    */

    'cache_path' => base_path('bootstrap/cache/filament'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Loading Delay
    |--------------------------------------------------------------------------
    */

    'livewire_loading_delay' => 'default',

    /*
    |--------------------------------------------------------------------------
    | System Route Prefix
    |--------------------------------------------------------------------------
    */

    'system_route_prefix' => env(
        'FILAMENT_SYSTEM_ROUTE_PREFIX',
        env('FILAMENT_CORE_PATH', 'filament'),
    ),

];
