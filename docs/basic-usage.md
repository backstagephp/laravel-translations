# Basic Usage

This guide covers the core features of the Laravel Translations package for managing translations in your Laravel application.

## Overview

The Laravel Translations package extends Laravel's built-in translation system with:

- Automatic scanning of translation strings
- Multiple translation providers (Google Translate, DeepL, AI)
- Language management
- Translation synchronization
- Performance optimization with caching

## Language Management

### Adding Languages

Add new languages to your application:

```bash
# Add a language with locale and display name
php artisan translations:languages:add en English
php artisan translations:languages:add es Spanish
php artisan translations:languages:add fr French

# Add locale-specific variants
php artisan translations:languages:add en-US "English (US)"
php artisan translations:languages:add fr-BE "Français (Belgique)"
```

### Managing Languages

Languages are stored in the `languages` table with these fields:

- `code` - Language code (e.g., 'en', 'es', 'fr-BE')
- `name` - Display name in your app's locale
- `native` - Native name of the language
- `active` - Whether the language is active
- `default` - Whether it's the default language

### Working with Languages in Code

```php
use Backstage\Translations\Laravel\Models\Language;

// Get all active languages
$languages = Language::active()->get();

// Get default language
$default = Language::default();

// Get specific language
$spanish = Language::where('code', 'es')->first();

// Check if language exists
if (Language::where('code', 'de')->exists()) {
    // German language is available
}
```

## Scanning for Translations

### Automatic Scanning

The package automatically scans your application for translation strings:

```bash
# Scan all active languages
php artisan translations:scan

# Scan specific language
php artisan translations:scan --language=es
```

### What Gets Scanned

The scanner looks for these Laravel translation functions:

```php
// In your PHP files
trans('messages.welcome');
__('messages.hello');
trans_choice('messages.apples', $count);

// In Blade templates
{{ __('messages.welcome') }}
{{ trans('messages.hello') }}
@lang('messages.goodbye')
@choice('messages.items', $count)

// Using Lang facade
Lang::get('messages.welcome');
Lang::trans('messages.hello');
Lang::choice('messages.apples', $count);
```

### Translation Keys Structure

Scanned translations are stored with this structure:

- `group` - The translation group (e.g., 'messages', 'validation')
- `key` - The translation key (e.g., 'welcome', 'hello')
- `namespace` - The namespace (usually '*' for default)
- `code` - The language code
- `text` - The actual translation text

## Translating Strings

### Automatic Translation

Translate all scanned strings:

```bash
# Translate all languages
php artisan translations:translate

# Translate specific language
php artisan translations:translate --code=es

# Update existing translations
php artisan translations:translate --code=es --update
```

### Translation Process

1. **Scan** - Find translation strings in your code
2. **Store** - Save them to the database
3. **Translate** - Use configured provider to translate
4. **Cache** - Store translations for performance

### Translation Providers

The package supports multiple translation providers. See the [Translation Providers](providers.md) guide for detailed configuration.

## Using Translations

### Standard Laravel Methods

Use translations exactly like Laravel's built-in system:

```php
// In controllers
$message = trans('messages.welcome');
$message = __('messages.hello');

// In Blade templates
{{ __('messages.welcome') }}
{{ trans('messages.hello') }}

// With parameters
{{ __('messages.welcome', ['name' => $user->name]) }}

// Pluralization
{{ trans_choice('messages.apples', $count) }}
```

### Programmatic Access

Access translations directly from the database:

```php
use Backstage\Translations\Laravel\Models\Translation;

// Get specific translation
$translation = Translation::where('key', 'welcome')
    ->where('group', 'messages')
    ->where('code', 'es')
    ->first();

echo $translation->text; // "Bienvenido"

// Get all translations for a language
$spanishTranslations = Translation::where('code', 'es')->get();

// Get all translations for a group
$messages = Translation::where('group', 'messages')->get();
```

## Synchronization

### Automatic Sync

The package automatically syncs translations when:

- New languages are added
- Translation strings are scanned
- Model attributes are updated

### Manual Sync

Force synchronization:

```bash
php artisan translations:sync
```

This command:
- Removes orphaned translations
- Fills missing translations
- Updates translation status

### Scheduled Sync

The package automatically runs sync daily at midnight:

```php
// In TranslationServiceProvider
Schedule::command(SyncTranslations::class)
    ->dailyAt('00:00')
    ->withoutOverlapping();
```

## Caching

### Enable Caching

For better performance, enable permanent caching:

```php
// In config/translations.php
'use_permanent_cache' => true,
```

### Cache Management

The package automatically manages translation caches:

- Translations are cached when saved
- Cache is updated when translations change
- Cache is cleared when needed

## Best Practices

### 1. Organize Translation Keys

Use meaningful group and key names:

```php
// Good
trans('auth.login.title')
trans('products.show.price')
trans('validation.required')

// Avoid
trans('a')
trans('b')
trans('c')
```

### 2. Use Parameters

Make translations flexible with parameters:

```php
// Translation file
'welcome' => 'Welcome, :name!',

// Usage
trans('messages.welcome', ['name' => $user->name])
```

### 3. Handle Missing Translations

The package handles missing translations gracefully:

```php
// If translation doesn't exist, returns the key
echo trans('nonexistent.key'); // "nonexistent.key"

// Check if translation exists
if (Translation::where('key', 'welcome')->exists()) {
    // Translation exists
}
```

### 4. Regular Maintenance

Run these commands regularly:

```bash
# Scan for new translations
php artisan translations:scan

# Translate new strings
php artisan translations:translate

# Sync and clean up
php artisan translations:sync
```

## Troubleshooting

### Common Issues

**Translations not appearing**: Run `translations:scan` first

**Translation errors**: Check your translation provider configuration

**Performance issues**: Enable caching with `use_permanent_cache => true`

**Missing languages**: Add languages with `translations:languages:add`

## Next Steps

- [Model Attributes](model-attributes.md) - Translate Eloquent model attributes
- [Commands](commands.md) - Complete command reference
- [Advanced Usage](advanced-usage.md) - Advanced features and customization
