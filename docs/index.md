# Laravel Translations Documentation

Welcome to the Laravel Translations package documentation. This package provides comprehensive translation management for Laravel applications with support for multiple translation providers and automatic model attribute translation.

## Quick Navigation

- [Installation & Setup](installation.md) - Get started quickly
- [Configuration](configuration.md) - Configure the package
- [Basic Usage](basic-usage.md) - Core translation features
- [Model Attributes](model-attributes.md) - Translate Eloquent model attributes
- [Translation Providers](providers.md) - Google Translate, DeepL, AI providers
- [Commands](commands.md) - Artisan commands reference
- [Advanced Usage](advanced-usage.md) - Advanced features and customization

## What This Package Does

Laravel Translations is a powerful package that:

1. **Scans your Laravel application** for translation strings using `trans()`, `__()`, and other Laravel translation functions
2. **Manages multiple languages** with support for locale-specific variants (e.g., `en-US`, `fr-BE`)
3. **Translates automatically** using Google Translate, DeepL, or AI providers
4. **Handles model attributes** - automatically translate Eloquent model attributes
5. **Syncs translations** - keeps your translations in sync across languages
6. **Caches efficiently** - optional permanent caching for better performance

## Key Features

- 🌐 **Multiple Translation Providers**: Google Translate, DeepL, AI (OpenAI, etc.)
- 🔄 **Automatic Scanning**: Finds translation strings in your codebase
- 🏷️ **Model Attributes**: Translate Eloquent model attributes automatically
- 📊 **Language Management**: Add, remove, and manage languages easily
- ⚡ **Performance**: Optional caching and queued operations
- 🎯 **Laravel Integration**: Seamless integration with Laravel's translation system

## Quick Start

1. **Install the package**:
   ```bash
   composer require backstage/laravel-translations
   ```

2. **Publish and run migrations**:
   ```bash
   php artisan vendor:publish --provider="Backstage\Translations\Laravel\TranslationServiceProvider"
   php artisan migrate
   ```

3. **Add a language**:
   ```bash
   php artisan translations:languages:add en English
   ```

4. **Scan for translations**:
   ```bash
   php artisan translations:scan
   ```

5. **Translate them**:
   ```bash
   php artisan translations:translate
   ```

That's it! Your translations are now managed automatically.

## Need Help?

- Look at the [Advanced Usage](advanced-usage.md) for complex scenarios

---

*This documentation covers Laravel Translations v1.x. For older versions, please refer to the appropriate version tag.*
