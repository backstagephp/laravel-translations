# Commands Reference

The Laravel Translations package provides several Artisan commands to manage translations. This guide covers all available commands and their options.

## Available Commands

### `translations:languages:add`

Add a new language to your application.

```bash
php artisan translations:languages:add {locale} {label}
```

**Parameters:**
- `locale` - Language code (e.g., 'en', 'es', 'fr-BE')
- `label` - Display name for the language

**Examples:**
```bash
# Add basic languages
php artisan translations:languages:add en English
php artisan translations:languages:add es Spanish
php artisan translations:languages:add fr French

# Add locale-specific variants
php artisan translations:languages:add en-US "English (US)"
php artisan translations:languages:add fr-BE "Français (Belgique)"
php artisan translations:languages:add de-AT "Deutsch (Österreich)"
```

**What it does:**
- Creates a new language record in the `languages` table
- Sets the language as active by default
- Generates native language name automatically

### `translations:scan`

Scan your application for translation strings.

```bash
php artisan translations:scan
```

**Options:**
- `--language={code}` - Scan for specific language only

**Examples:**
```bash
# Scan all active languages
php artisan translations:scan

# Scan for specific language
php artisan translations:scan --language=es
```

**What it does:**
- Scans configured paths for translation functions
- Finds `trans()`, `__()`, `@lang`, etc.
- Stores found translations in the database
- Creates language records if they don't exist

**Configuration:**
The scan behavior is controlled by `config/translations.php`:

```php
'scan' => [
    'paths' => [
        base_path(), // Directories to scan
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

### `translations:translate`

Translate scanned translation strings.

```bash
php artisan translations:translate
    {--code= : Translate for specific language}
    {--update : Update existing translations}
```

**Options:**
- `--code={language}` - Translate only for specific language
- `--update` - Overwrite existing translations

**Examples:**
```bash
# Translate all languages
php artisan translations:translate

# Translate specific language
php artisan translations:translate --code=es

# Update existing translations
php artisan translations:translate --code=es --update

# Update all translations
php artisan translations:translate --update
```

**What it does:**
- Uses configured translation provider
- Translates untranslated strings
- Updates `translated_at` timestamp
- Processes translations in background (queued)

### `translations:sync`

Synchronize translations and clean up orphaned data.

```bash
php artisan translations:sync
```

**What it does:**
- Removes orphaned translations
- Fills missing translations
- Updates translation status
- Cleans up unused data

**When to use:**
- After adding/removing languages
- After cleaning up translation files
- For general maintenance
- When translations seem out of sync

## Command Workflows

### Initial Setup

Set up translations for a new application:

```bash
# 1. Add your default language
php artisan translations:languages:add en English

# 2. Add additional languages
php artisan translations:languages:add es Spanish
php artisan translations:languages:add fr French

# 3. Scan for existing translations
php artisan translations:scan

# 4. Translate all strings
php artisan translations:translate
```

### Adding New Language

Add a new language to existing application:

```bash
# 1. Add the language
php artisan translations:languages:add de German

# 2. Scan for new translations
php artisan translations:scan

# 3. Translate for new language
php artisan translations:translate --code=de

# 4. Sync to ensure consistency
php artisan translations:sync
```

### Updating Translations

Update existing translations:

```bash
# Update specific language
php artisan translations:translate --code=es --update

# Update all languages
php artisan translations:translate --update
```

### Maintenance

Regular maintenance tasks:

```bash
# Scan for new translations
php artisan translations:scan

# Translate new strings
php artisan translations:translate

# Sync and clean up
php artisan translations:sync
```

## Scheduled Commands

The package automatically schedules the sync command:

```php
// In TranslationServiceProvider
Schedule::command(SyncTranslations::class)
    ->dailyAt('00:00')
    ->withoutOverlapping();
```

This runs daily at midnight to keep translations in sync.

## Custom Commands

You can create custom commands that use the translation system:

```php
<?php

namespace App\Console\Commands;

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Console\Command;

class TranslationStats extends Command
{
    protected $signature = 'translations:stats';
    protected $description = 'Show translation statistics';

    public function handle()
    {
        $languages = Language::active()->count();
        $translations = Translation::count();
        $translated = Translation::whereNotNull('translated_at')->count();

        $this->info("Languages: {$languages}");
        $this->info("Total translations: {$translations}");
        $this->info("Translated: {$translated}");
        $this->info("Pending: " . ($translations - $translated));
    }
}
```

## Command Output

### Success Messages

Commands provide clear feedback:

```bash
$ php artisan translations:languages:add es Spanish
✓ Language 'es' added successfully

$ php artisan translations:scan
✓ Scanning translations...
✓ Found 150 translation strings
✓ Translations stored successfully

$ php artisan translations:translate --code=es
✓ Translating for language: Spanish...
✓ 150 translations processed successfully
```

### Error Handling

Commands handle errors gracefully:

```bash
$ php artisan translations:languages:add es Spanish
✗ Language 'es' already exists

$ php artisan translations:translate --code=xx
✗ Language 'xx' not found

$ php artisan translations:translate --code=es
✗ Translation failed: API key not configured
```

## Integration with CI/CD

### GitHub Actions

Example workflow for automated translation updates:

```yaml
name: Update Translations

on:
  schedule:
    - cron: '0 2 * * *'  # Daily at 2 AM

jobs:
  update-translations:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          
      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader
        
      - name: Run migrations
        run: php artisan migrate --force
        
      - name: Scan translations
        run: php artisan translations:scan
        
      - name: Translate new strings
        run: php artisan translations:translate
        env:
          TRANSLATION_DRIVER: google-translate
          
      - name: Sync translations
        run: php artisan translations:sync
```

### Local Development

Set up local development workflow:

```bash
# Add to your local development script
#!/bin/bash

# Scan for new translations
php artisan translations:scan

# Translate new strings
php artisan translations:translate

# Sync translations
php artisan translations:sync

echo "Translations updated successfully!"
```

## Troubleshooting Commands

### Common Issues

**Command not found**: Ensure the package is properly installed and registered

**Permission errors**: Check file permissions for the application directory

**Database errors**: Ensure migrations have been run

**Translation failures**: Check your translation provider configuration

### Debug Mode

Enable debug mode for more verbose output:

```bash
# Set debug mode
APP_DEBUG=true php artisan translations:scan

# Or use verbose flag
php artisan translations:scan -v
```

### Logging

Commands log their activities:

```php
// Check logs
tail -f storage/logs/laravel.log | grep translation
```

## Next Steps

- [Advanced Usage](advanced-usage.md) - Advanced features and customization
