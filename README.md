<div align="center">

# Laravel Translations

 Nice to meet you, we're [Vormkracht10](https://vormrkacht10.nl)

<em>Break Language Barriers, Empower Global Success</em>

<img src="https://img.shields.io/github/license/backstagephp/laravel-translations?style=flat&logo=opensourceinitiative&logoColor=white&color=0080ff" alt="license">
<img src="https://img.shields.io/github/last-commit/backstagephp/laravel-translations?style=flat&logo=git&logoColor=white&color=0080ff" alt="last-commit">
<span>
    <a href="https://packagist.org/packages/backstage/laravel-translations">
        <img src="https://img.shields.io/packagist/v/backstage/laravel-translations.svg?style=flat-square" alt="Latest Version on Packagist">
    </a>
    <a href="https://github.com/backstagephp/laravel-translations/actions?query=tests">
        <img src="https://img.shields.io/github/actions/workflow/status/backstagephp/laravel-translations/run-tests.yml?branch=main&label=tests&style=flat-square" alt="GitHub Tests Action Status">
    </a>
    <a href="https://github.com/backstagephp/laravel-translations/actions?query=pint">
        <img src="https://img.shields.io/github/actions/workflow/status/backstagephp/laravel-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square" alt="GitHub Code Style Action Status">
    </a>
    <a href="https://packagist.org/packages/backstage/laravel-translations">
        <img src="https://img.shields.io/packagist/dt/backstage/laravel-translations.svg?style=flat-square" alt="Total Downloads">
    </a>
</span>

</div>

## Quick Start

Laravel Translations makes multilingual Laravel applications simple. Scan, translate, and manage your translations automatically.

### 1. Install

```bash
composer require backstage/laravel-translations

php artisan vendor:publish --provider="Backstage\Translations\Laravel\TranslationServiceProvider"

php artisan migrate
```

### 2. Add Languages

```bash
php artisan translations:languages:add en English

php artisan translations:languages:add es Spanish

php artisan translations:languages:add fr French
```

### 3. Scan & Translate

```bash
# Scan your app for translation strings
php artisan translations:scan

# Translate them automatically
php artisan translations:translate
```

That's it! Your translations are now managed automatically.

## Features

- 🌐 **Multiple Providers**: Google Translate, DeepL, AI (OpenAI, etc.)
- 🔄 **Auto-Scanning**: Finds `trans()`, `__()`, `@lang` in your code
- 🏷️ **Model Attributes**: Translate Eloquent model attributes automatically
- 📊 **Language Management**: Add, remove, and manage languages easily
- ⚡ **Performance**: Optional caching and queued operations
- 🎯 **Laravel Integration**: Works seamlessly with Laravel's translation system

## Model Translation

Translate Eloquent model attributes automatically. See the [Model Attributes](docs/model-attributes.md) guide for detailed setup and usage.

## Commands

```bash
# Add languages
php artisan translations:languages:add {locale} {label}

# Scan for translations
php artisan translations:scan

# Translate strings
php artisan translations:translate

php artisan translations:translate --code=es

php artisan translations:translate --update

# Sync translations
php artisan translations:sync
```

## Translation Providers

Supports Google Translate, DeepL, and AI providers. See the [Translation Providers](docs/providers.md) guide for configuration details.

## Documentation

📚 **[Complete Documentation](docs/index.md)** - Detailed guides and API reference

- [Installation & Setup](docs/installation.md)
- [Configuration](docs/configuration.md)
- [Basic Usage](docs/basic-usage.md)
- [Model Attributes](docs/model-attributes.md)
- [Translation Providers](docs/providers.md)
- [Commands Reference](docs/commands.md)
- [Advanced Usage](docs/advanced-usage.md)

## Requirements

- PHP 8.2+
- Laravel 10.x, 11.x, or 12.x

## License

MIT License. See [LICENSE](LICENSE.md) for details.

## Contributing

- 🐛 [Report Issues](https://github.com/backstagephp/laravel-translations/issues)
- 💡 [Submit Pull Requests](https://github.com/backstagephp/laravel-translations/pulls)

---

<div align="center">
Made with ❤️ by [Vormkracht10](https://vormkracht10.nl)
</div>
