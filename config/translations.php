<?php

use EchoLabs\Prism\Enums\Provider;

return [
    'scan' => [
        'paths' => [
            app_path(),
            resource_path('views'),
        ],
        'files' => [
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
        'default' => env('TRANSLATION_DRIVER', 'google'),

        'drivers' => [
            'google' => [
                // no options
            ],

            'ai' => [
                'provider' => Provider::OpenAI,
                'model' => 'text-davinci-003',
                'system_prompt' => 'You are an expert mathematician who explains concepts simply. The only thing you do it output what i ask. No comments, no extra information. Just the answer.',
            ],
        ],
    ],
];
