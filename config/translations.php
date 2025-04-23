<?php

use Prism\Prism\Enums\Provider;

return [
    'scan' => [
        'paths' => [
            base_path(),
        ],

        'extensions' => [
            '*.php',
            '*.blade.php',
            '*.json',
        ],

        'functions' => [
            'trans',
            'trans_choice',
            'Lang::transChoice',
            'Lang::trans',
            'Lang::get',
            'Lang::choice',
            '@lang',
            '@choice',
            '__',
        ],
    ],

    'translators' => [
        'default' => env('TRANSLATION_DRIVER', 'google-translate'),

        'drivers' => [
            'google-translate' => [
                // no options
            ],

            'ai' => [
                'provider' => Provider::OpenAI,
                'model' => 'gpt-4.1',
                'system_prompt' => 'You translate Laravel translations strings to the language you have been asked.',
            ],
        ],
    ],
];
