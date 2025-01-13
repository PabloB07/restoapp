<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Would you like the install button to appear on all pages?
      Set true/false
    |--------------------------------------------------------------------------
    */

    'install-button' => true,

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Configuration
    |--------------------------------------------------------------------------
    |  php artisan erag:pwa-update-manifest
    */

    'manifest' => [
        'name' => 'Vastago Restaurante',
        'short_name' => 'VASTAGO',
        'background_color' => '#000000',
        'display' => 'fullscreen',
        'description' => 'Sistema de gestión Vastago',
        'theme_color' => '#000000',
        'icons' => [
            [
                'src' => 'images/vastago_white.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
            [
                'src' => 'images/vastago_black.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
            [
                'src' => 'images/vastago_white.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
            [
                'src' => 'images/vastago_white.png',
                'sizes' => '144x144',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
        ],
        'start_url' => '/',
        'orientation' => 'portrait',
        'categories' => ['food', 'business'],
        'shortcuts' => [
            [
                'name' => 'Dashboard',
                'url' => '/dashboard',
                'icons' => [
                    [
                        'src' => 'images/vastago_white.png',
                        'sizes' => '96x96',
                        'type' => 'image/png',
                        'purpose' => 'any maskable',
                    ]
                ]
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Configuration
    |--------------------------------------------------------------------------
    | Toggles the application's debug mode based on the environment variable
    */

    'debug' => env('APP_DEBUG', false),

];
