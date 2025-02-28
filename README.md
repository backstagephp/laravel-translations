# Backstage: Laravel translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/backstage/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/backstage/laravel-translations)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/backstagephp/laravel-translations/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/backstagephp/laravel-translations/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/backstagephp/laravel-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/backstagephp/laravel-translations/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/backstage/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/backstage/laravel-translations)

## Nice to meet you, we're [Vormkracht10](https://vormrkacht10.nl)

Hi! We are a web development agency from Nijmegen in the Netherlands and we use Laravel for everything: advanced websites with a lot of bells and whitles and large web applications.

## Installation

You can install the package via composer:

```bash
composer require backstage/laravel-translations
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Backstage\Translations\Laravel\TranslationServiceProvider"
php artisan migrate
```

This is the contents of the published config file:

```php
use EchoLabs\Prism\Enums\Provider;

[
    'scan' => [
        'paths' => [
            base_path(),
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
        'default' => env('TRANSLATION_DRIVER', 'google-translate'),

        'drivers' => [
            'google-translate' => [
                // no options
            ],

            'ai' => [
                'provider' => Provider::OpenAI, // Example provider
                'model' => 'text-davinci-003', // Example model
                'system_prompt' => 'You are an expert mathematician who explains concepts simply. The only thing you do it output what i ask. No comments, no extra information. Just the answer.', // Example system prompt
            ],
        ],
    ],
];

```

If you have choosen the AI driver, please read the [Prism documentation](https://prism.echolabs.dev/providers/anthropic.html) on how to configure providers.

## Usage

### Add lang types

If you want to add a language use the following command:

```bash
php artisan translations:languages:add {locale} {label}
```

For example:

```bash
php artisan translations:languages:add nl Nederlands

translations:languages:add en English

translations:languages:add fr-BE Français // French specifically for Belgians
```

The command can also be used without in-command-line parameters

### Scan for translations

To scan for translations within your Laravel application, use the following command:

```bash
php artisan translations:scan
```

### Translate scanned translations

To translate the scanned translations, use the following command:

```bash
php artisan translations:translate
        {--all : Translate language strings for all languages}
        {--code= : Translate language strings for a specific language}
        {--update : Update and overwrite existing translations}
```

For example:

```bash
php artisan translations:translate --code=nl

php artisan translations:translate --code=en

php artisan translations:translate --code=fr-BE --update // overwrite existing translations

php artisan translations:translate // translate all languages
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Manoj Hortulanus](https://github.com/arduinomaster22)
-   [Mark van Eijk](https://github.com/markvaneijk)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
