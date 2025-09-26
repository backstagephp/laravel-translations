# Configuration

This document covers all configuration options available in the Laravel Translations package.

## Configuration File

The main configuration file is located at `config/translations.php`. Here's the complete configuration with explanations:

```php
<?php

use DeepL\TranslatorOptions;
use Prism\Prism\Enums\Provider;

return [
    // Scan configuration
    'scan' => [
        'paths' => [
            base_path(), // Directories to scan for translations
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

    // Enable permanent caching for better performance
    'use_permanent_cache' => false,

    // Eloquent model configuration
    'eloquent' => [
        'translatable-models' => [
            // Add your models here
            // App\Models\Post::class,
            // App\Models\Product::class,
        ],
    ],

    // Translation providers configuration
    'translators' => [
        'default' => env('TRANSLATION_DRIVER', 'google-translate'),
        
        'drivers' => [
            'google-translate' => [
                // No additional options needed
            ],
            
            'ai' => [
                'provider' => Provider::OpenAI,
                'model' => 'gpt-4.1',
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
```

## Configuration Sections

### Scan Configuration

Controls how the package scans your application for translation strings.

#### `paths`
Array of directories to scan for translation strings.

```php
'paths' => [
    base_path(),                    // Scan entire application
    base_path('app'),              // Scan only app directory
    base_path('resources/views'),  // Scan only views
],
```

#### `extensions`
File extensions to include in the scan.

```php
'extensions' => [
    '*.php',        // PHP files
    '*.blade.php',  // Blade templates
    '*.json',       // JSON files
    '*.vue',        // Vue components
],
```

#### `functions`
Laravel translation functions to detect.

```php
'functions' => [
    'trans',           // trans() function
    'trans_choice',    // trans_choice() function
    '__',              // __() helper
    '@lang',           // @lang directive
    '@choice',         // @choice directive
    'Lang::get',       // Lang facade methods
    'Lang::trans',
    'Lang::choice',
    'Lang::transChoice',
],
```

### Caching Configuration

#### `use_permanent_cache`
Enable permanent caching for better performance. Requires the `backstage/laravel-permanent-cache` package.

```php
'use_permanent_cache' => true, // Enable caching
```

### Eloquent Configuration

#### `translatable-models`
Register models that should have translatable attributes.

```php
'eloquent' => [
    'translatable-models' => [
        App\Models\Post::class,
        App\Models\Product::class,
        App\Models\Category::class,
    ],
],
```

### Translation Providers

#### Default Driver
Set the default translation provider.

```php
'default' => env('TRANSLATION_DRIVER', 'google-translate'),
```

#### Google Translate Driver
No additional configuration needed.

```php
'google-translate' => [
    // No options required
],
```

#### DeepL Driver
Configure DeepL API settings.

```php
'deep-l' => [
    'options' => [
        TranslatorOptions::SERVER_URL => env('DEEPL_SERVER_URL', 'https://api.deepl.com/'),
    ],
],
```

**Environment Variables:**
```env
DEEPL_API_KEY=your_deepl_api_key
DEEPL_SERVER_URL=https://api.deepl.com/  # or https://api-free.deepl.com/ for free tier
```

#### AI Driver
Configure AI translation providers.

```php
'ai' => [
    'provider' => Provider::OpenAI,
    'model' => 'gpt-4.1',
    'system_prompt' => 'You translate Laravel translations strings to the language you have been asked.',
],
```

**Environment Variables:**
```env
OPENAI_API_KEY=your_openai_api_key
```

## Environment Variables

Add these to your `.env` file:

```env
# Translation driver
TRANSLATION_DRIVER=google-translate

# DeepL configuration
DEEPL_API_KEY=your_deepl_api_key
DEEPL_SERVER_URL=https://api.deepl.com/

# AI provider configuration
OPENAI_API_KEY=your_openai_api_key
```

## Advanced Configuration

### Custom Translation Functions

Add custom translation functions to scan:

```php
'functions' => [
    'trans',
    '__',
    'my_custom_trans',  // Custom function
    'MyClass::translate', // Static method
],
```

### Excluding Paths

To exclude certain paths from scanning, modify the paths array:

```php
'paths' => [
    base_path('app'),
    base_path('resources/views'),
    // Exclude vendor and storage
    // base_path('vendor'),
    // base_path('storage'),
],
```

### Custom AI Prompts

Customize AI translation prompts:

```php
'ai' => [
    'provider' => Provider::OpenAI,
    'model' => 'gpt-4.1',
    'system_prompt' => 'You are a professional translator specializing in Laravel applications. Translate the following text accurately while maintaining the context and meaning.',
],
```

## Configuration Validation

The package validates your configuration on boot. Common validation errors:

- **Invalid driver**: Ensure the driver exists in the `drivers` array
- **Missing API keys**: Check that required environment variables are set
- **Invalid paths**: Ensure scan paths exist and are readable

## Performance Optimization

### Enable Caching
For better performance, enable permanent caching:

```php
'use_permanent_cache' => true,
```

### Optimize Scan Paths
Only scan necessary directories:

```php
'paths' => [
    base_path('app'),
    base_path('resources/views'),
    // Don't scan vendor, storage, etc.
],
```

### Queue Heavy Operations
Translation operations are automatically queued for better performance.

## Next Steps

- [Basic Usage](basic-usage.md) - Learn how to use the configured package
- [Model Attributes](model-attributes.md) - Set up model translation
- [Commands](commands.md) - Use Artisan commands effectively
