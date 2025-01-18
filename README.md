# Laravel translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vormkracht10/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/laravel-translations)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vormkracht10/laravel-translations/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vormkracht10/laravel-translations/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vormkracht10/laravel-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vormkracht10/laravel-translations/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vormkracht10/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/laravel-translations)

## Installation

You can install the package via composer:

```bash
composer require vormkracht10/laravel-translations
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-translations-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-translations-config"
```

This is the contents of the published config file:

```php
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
```

For OpenAI add this to the .env:
```env
OPENAI_API_KEY=SECRET
```

## Usage

### Add lang types

If you want to add a language use the following command:
```bash
php artisan translations:lang {locale} {label}
```

For example:
```bash
php artisan translations:lang nl Nederlands

php artisan translations:lang en English

php artisan translations:lang fr French
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
php artisan translations:translate-keys 
            {--all : Translate imports for all languages} 
            {--lang= : Translate imports for a specific language}
```

For example:
```bash
php artisan translations:translate-keys --lang="nl"

php artisan translations:translate-keys --lang="en"

php artisan translations:translate-keys --lang="fr"

php artisan translations:translate-keys --all
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

- [Manoj Hortulanus](https://github.com/arduinomaster22)
- [Mark van Eijk](https://github.com/markvaneijk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
