<?php

use DeepL\TranslatorOptions;
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

    'use_permanent_cache' => false,

    'ai_client_options' => [
        'timeout' => 600,
        'text_output_only' => true,
    ],

    'ai_provider_options' => [
        'reasoning' => ['effort' => 'high'],
    ],

    'eloquent' => [
        'translatable-models' => [
            //
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
                'model' => 'gpt-5.4',
                'system_prompt' => 'You translate Laravel translations strings to the language you have been asked.',
            ],

            'deep-l' => [
                'options' => [
                    TranslatorOptions::SERVER_URL => env('DEEPL_SERVER_URL', 'https://api.deepl.com/'),
                ],
            ],
        ],
    ],
];
