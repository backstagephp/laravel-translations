# Installation & Setup

This guide will walk you through installing and setting up the Laravel Translations package.

## Requirements

- PHP 8.2 or higher
- Laravel 10.x, 11.x, or 12.x
- Composer

## Installation

### 1. Install via Composer

```bash
composer require backstage/laravel-translations
```

### 2. Publish Configuration and Migrations

Publish the package configuration and migration files:

```bash
php artisan vendor:publish --provider="Backstage\Translations\Laravel\TranslationServiceProvider"
```

This will create:
- `config/translations.php` - Package configuration
- Database migrations for `languages`, `translations`, and `translated_attributes` tables

### 3. Run Migrations

Create the necessary database tables:

```bash
php artisan migrate
```

### 4. Configure Environment Variables

Add these environment variables to your `.env` file:

```env
# Translation driver (google-translate, deep-l, ai)
TRANSLATION_DRIVER=google-translate

# DeepL configuration (if using DeepL)
DEEPL_API_KEY=your_deepl_api_key
DEEPL_SERVER_URL=https://api.deepl.com/

# AI provider configuration (if using AI)
OPENAI_API_KEY=your_openai_api_key
```

## Basic Configuration

### Translation Driver

The package supports three translation providers:

1. **Google Translate** (default) - No API key required
2. **DeepL** - Requires API key
3. **AI** - Uses OpenAI or other AI providers

Set your preferred driver in `config/translations.php`:

```php
'translators' => [
    'default' => env('TRANSLATION_DRIVER', 'google-translate'),
    // ...
],
```

### Scan Configuration

Configure which files and functions to scan for translations:

```php
'scan' => [
    'paths' => [
        base_path(), // Scan entire application
    ],
    'extensions' => [
        '*.php',
        '*.blade.php',
        '*.json',
    ],
    'functions' => [
        'trans',
        'trans_choice',
        '__',
        '@lang',
        '@choice',
        // ... more functions
    ],
],
```

## Initial Setup

### 1. Add Your First Language

Add your application's default language:

```bash
php artisan translations:languages:add en English
```

### 2. Scan for Existing Translations

Scan your application for translation strings:

```bash
php artisan translations:scan
```

This will find all translation keys in your codebase and store them in the database.

### 3. Add Additional Languages

Add more languages as needed:

```bash
php artisan translations:languages:add es Spanish
php artisan translations:languages:add fr French
php artisan translations:languages:add de German
```

### 4. Translate Your Strings

Translate all scanned strings:

```bash
php artisan translations:translate
```

Or translate specific languages:

```bash
php artisan translations:translate --code=es
php artisan translations:translate --code=fr
```

## Verification

Check that everything is working:

1. **Verify languages were created**:
   ```bash
   php artisan tinker
   >>> \Backstage\Translations\Laravel\Models\Language::all()
   ```

2. **Check translations were scanned**:
   ```bash
   php artisan tinker
   >>> \Backstage\Translations\Laravel\Models\Translation::count()
   ```

3. **Test translation retrieval**:
   ```bash
   php artisan tinker
   >>> app('translator')->get('your.translation.key', [], 'es')
   ```

## Next Steps

- [Basic Usage](basic-usage.md) - Learn how to use the core features
- [Model Attributes](model-attributes.md) - Set up automatic model translation
- [Configuration](configuration.md) - Detailed configuration options

## Troubleshooting

### Common Issues

**Migration fails**: Ensure your database connection is working and you have the necessary permissions.

**Translations not found**: Make sure you've run `translations:scan` after adding languages.

**Translation driver errors**: Check your API keys and configuration for the selected driver.

**Performance issues**: Consider enabling permanent caching in the configuration.
