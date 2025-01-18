<?php

return [
    'scan' => [
        'paths' => [
            base_path('app'),
            base_path('resources/views'),
        ],
    ],

    'translation' => [
        // Can be set to 'google' or 'openai'
        'driver' => env('TRANSLATION_DRIVER', 'google'),
    ],
];
