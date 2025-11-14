# Translation Providers

The Laravel Translations package supports multiple translation providers. This guide covers configuration and usage of each provider.

## Supported Providers

- **Google Translate** - Free, no API key required
- **DeepL** - High-quality translations, requires API key
- **AI Providers** - OpenAI, Anthropic, and other AI services

## Google Translate (Default)

### Configuration

Google Translate is the default provider and requires no additional configuration:

```php
// config/translations.php
'translators' => [
    'default' => 'google-translate',
    'drivers' => [
        'google-translate' => [
            // No configuration needed
        ],
    ],
],
```

### Usage

```bash
# Set as default in .env
TRANSLATION_DRIVER=google-translate

# Or use directly
php artisan translations:translate
```

### Features

- ✅ No API key required
- ✅ Supports 100+ languages
- ✅ Fast translation
- ✅ Free to use
- ❌ Lower translation quality
- ❌ Rate limits may apply

## DeepL

### Configuration

DeepL provides high-quality translations but requires an API key:

```php
// config/translations.php
'translators' => [
    'default' => 'deep-l',
    'drivers' => [
        'deep-l' => [
            'options' => [
                TranslatorOptions::SERVER_URL => env('DEEPL_SERVER_URL', 'https://api.deepl.com/'),
            ],
        ],
    ],
],
```

### Environment Variables

```env
# .env
TRANSLATION_DRIVER=deep-l
DEEPL_API_KEY=your_deepl_api_key
DEEPL_SERVER_URL=https://api.deepl.com/  # or https://api-free.deepl.com/ for free tier
```

### Usage

```bash
# Set environment variables
export DEEPL_API_KEY=your_api_key
export DEEPL_SERVER_URL=https://api.deepl.com/

# Run translations
php artisan translations:translate
```

### Features

- ✅ High-quality translations
- ✅ Supports 30+ languages
- ✅ Context-aware translation
- ✅ Professional results
- ❌ Requires API key
- ❌ Usage limits (free tier: 500k chars/month)

### API Key Setup

1. **Sign up** at [DeepL API](https://www.deepl.com/pro-api)
2. **Get API key** from your account
3. **Choose server**:
   - `https://api.deepl.com/` - Pro account
   - `https://api-free.deepl.com/` - Free account
4. **Set environment variables**

## AI Providers

### Configuration

AI providers offer flexible, context-aware translations:

```php
// config/translations.php
'translators' => [
    'default' => 'ai',
    'drivers' => [
        'ai' => [
            'provider' => Provider::OpenAI,
            'model' => 'gpt-4.1',
            'system_prompt' => 'You translate Laravel translations strings to the language you have been asked.',
        ],
    ],
],
```

### Supported Providers

#### OpenAI

```env
# .env
TRANSLATION_DRIVER=ai
OPENAI_API_KEY=your_openai_api_key
```

```php
'ai' => [
    'provider' => Provider::OpenAI,
    'model' => 'gpt-4.1', // or 'gpt-3.5-turbo'
    'system_prompt' => 'You are a professional translator...',
],
```

#### Anthropic (Claude)

```env
# .env
ANTHROPIC_API_KEY=your_anthropic_api_key
```

```php
'ai' => [
    'provider' => Provider::Anthropic,
    'model' => 'claude-3-sonnet-20240229',
    'system_prompt' => 'You are a professional translator...',
],
```

### Custom AI Providers

Create custom AI providers:

```php
// config/translations.php
'ai' => [
    'provider' => 'custom',
    'model' => 'custom-model',
    'api_key' => env('CUSTOM_AI_API_KEY'),
    'endpoint' => env('CUSTOM_AI_ENDPOINT'),
    'system_prompt' => 'Custom translation prompt...',
],
```

### Features

- ✅ Context-aware translations
- ✅ Customizable prompts
- ✅ High-quality results
- ✅ Supports any language
- ❌ Requires API key
- ❌ Higher cost
- ❌ Slower than other providers

## Provider Comparison

| Feature | Google Translate | DeepL | AI Providers |
|---------|------------------|-------|--------------|
| **Cost** | Free | Paid | Paid |
| **Quality** | Good | Excellent | Excellent |
| **Speed** | Fast | Fast | Slow |
| **Languages** | 100+ | 30+ | Any |
| **Context** | Basic | Good | Excellent |
| **Setup** | None | API Key | API Key |

## Switching Providers

### Runtime Switching

Change provider at runtime:

```php
// Using the facade
use Backstage\Translations\Laravel\Facades\Translator;

Translator::with('deep-l')->translate('Hello', 'es');

// Or using the contract directly
use Backstage\Translations\Laravel\Contracts\TranslatorContract;

app(TranslatorContract::class)->with('deep-l')->translate('Hello', 'es');
```

### Configuration Switching

Change default provider:

```php
// config/translations.php
'translators' => [
    'default' => 'deep-l', // Change this
    // ...
],
```

### Environment Switching

Use different providers for different environments:

```env
# .env.local
TRANSLATION_DRIVER=google-translate

# .env.production
TRANSLATION_DRIVER=deep-l
```

## Custom Providers

### Creating Custom Providers

Create a custom translation provider:

```php
<?php

namespace App\Translations\Providers;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;

class CustomTranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage, string $sourceLanguage = 'en'): string
    {
        // Your custom translation logic
        return $this->callCustomAPI($text, $targetLanguage, $sourceLanguage);
    }

    private function callCustomAPI(string $text, string $targetLanguage, string $sourceLanguage): string
    {
        // Implementation
    }
}
```

### Registering Custom Providers

Register your custom provider:

```php
// In a service provider
public function register()
{
    $this->app->bind(TranslatorContract::class, function ($app) {
        return new CustomTranslator();
    });
}
```

## Error Handling

### Provider Errors

Handle provider-specific errors:

```php
use Backstage\Translations\Laravel\Facades\Translator;

try {
    $translation = Translator::translate('Hello', 'es');
} catch (GoogleTranslateException $e) {
    // Handle Google Translate errors
    Log::error('Google Translate error: ' . $e->getMessage());
} catch (DeepLException $e) {
    // Handle DeepL errors
    Log::error('DeepL error: ' . $e->getMessage());
} catch (AIProviderException $e) {
    // Handle AI provider errors
    Log::error('AI provider error: ' . $e->getMessage());
}
```

### Fallback Providers

Set up fallback providers:

```php
// config/translations.php
'translators' => [
    'default' => 'deep-l',
    'fallback' => 'google-translate', // Fallback if primary fails
    'drivers' => [
        'deep-l' => [...],
        'google-translate' => [...],
    ],
],
```

## Performance Optimization

### Caching

Enable caching for better performance:

```php
// config/translations.php
'use_permanent_cache' => true,
```

### Rate Limiting

Handle rate limits gracefully:

```php
// For DeepL
'deep-l' => [
    'options' => [
        TranslatorOptions::SERVER_URL => env('DEEPL_SERVER_URL'),
        'rate_limit' => 500, // requests per minute
    ],
],
```

### Batch Processing

Process translations in batches:

```php
use Backstage\Translations\Laravel\Facades\Translator;

// Translate multiple strings at once
$translations = Translator::translateBatch([
    'Hello' => 'es',
    'World' => 'es',
    'Laravel' => 'es',
]);
```

## Monitoring and Logging

### Translation Logging

Log translation activities:

```php
// In your service provider
Event::listen(TranslationCompleted::class, function ($event) {
    Log::info('Translation completed', [
        'provider' => $event->provider,
        'source' => $event->sourceLanguage,
        'target' => $event->targetLanguage,
        'text' => $event->text,
        'translation' => $event->translation,
    ]);
});
```

### Performance Monitoring

Monitor translation performance:

```php
// Track translation times
$start = microtime(true);
$translation = app('translator')->translate('Hello', 'es');
$time = microtime(true) - $start;

Log::info('Translation performance', [
    'provider' => 'deep-l',
    'time' => $time,
    'text_length' => strlen('Hello'),
]);
```

## Best Practices

### 1. Choose the Right Provider

- **Development**: Google Translate (free, fast)
- **Production**: DeepL (quality, reliability)
- **Specialized**: AI providers (context, customization)

### 2. Handle Errors Gracefully

```php
use Backstage\Translations\Laravel\Facades\Translator;

try {
    $translation = Translator::translate($text, $language);
} catch (Exception $e) {
    // Fallback to original text
    $translation = $text;
    Log::warning('Translation failed, using original text');
}
```

### 3. Cache Translations

Enable caching to avoid re-translating the same text:

```php
'use_permanent_cache' => true,
```

### 4. Monitor Usage

Track API usage and costs:

```php
// Log API usage
Log::info('Translation API usage', [
    'provider' => 'deep-l',
    'characters' => strlen($text),
    'cost' => $this->calculateCost($text),
]);
```

## Troubleshooting

### Common Issues

**API key errors**: Check your API key configuration

**Rate limit errors**: Implement rate limiting or switch providers

**Translation quality**: Try different providers or adjust prompts

**Performance issues**: Enable caching and optimize batch processing

### Debug Mode

Enable debug mode for detailed logging:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## Next Steps

- [Configuration](configuration.md) - Complete configuration options
- [Advanced Usage](advanced-usage.md) - Advanced features and customization
