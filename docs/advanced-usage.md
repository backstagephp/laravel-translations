# Advanced Usage

This guide covers advanced features and customization options for the Laravel Translations package.

## Custom Translation Functions

### Adding Custom Functions

Add custom translation functions to scan:

```php
// config/translations.php
'scan' => [
    'functions' => [
        'trans',
        '__',
        'my_custom_trans',        // Custom function
        'MyClass::translate',     // Static method
        'MyClass::trans',         // Another static method
    ],
],
```

### Creating Custom Functions

```php
// In a helper file or service
function my_custom_trans($key, $replace = [], $locale = null)
{
    return app('translator')->get($key, $replace, $locale);
}

// Or as a static method
class TranslationHelper
{
    public static function translate($key, $replace = [], $locale = null)
    {
        return app('translator')->get($key, $replace, $locale);
    }
}
```

## Custom Translation Prompts

### AI Provider Customization

Customize AI translation prompts:

```php
// config/translations.php
'ai' => [
    'provider' => Provider::OpenAI,
    'model' => 'gpt-4.1',
    'system_prompt' => 'You are a professional translator specializing in Laravel applications. Translate the following text accurately while maintaining the context and meaning.',
    'custom_prompts' => [
        'title' => 'Translate this title for a blog post about technology. Keep it engaging and SEO-friendly.',
        'content' => 'Translate this blog post content. Maintain the original tone and structure, including any HTML formatting.',
        'meta_description' => 'Translate this meta description for SEO. Keep it under 160 characters and compelling.',
    ],
],
```

### Context-Aware Translation

Use model context for better translations:

```php
class Post extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes;

    public function translateAttribute(mixed $attribute, string $targetLanguage, bool $overwrite = false, ?string $extraPrompt = null): mixed
    {
        // Build context-aware prompt
        $context = $this->buildTranslationContext($attribute);
        $prompt = $extraPrompt ?? $context;

        return TranslateAttribute::run(
            model: $this,
            attribute: $attribute,
            targetLanguage: $targetLanguage,
            overwrite: $overwrite,
            extraPrompt: $prompt
        );
    }

    private function buildTranslationContext(string $attribute): string
    {
        $context = "Translate this {$attribute} for a blog post";
        
        if ($this->category) {
            $context .= " in the {$this->category} category";
        }
        
        if ($this->tags) {
            $context .= " with tags: " . $this->tags->pluck('name')->join(', ');
        }
        
        return $context;
    }
}
```

## Event Handling

### Translation Events

Listen to translation events:

```php
use Backstage\Translations\Laravel\Events\LanguageAdded;
use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Events\LanguageCodeChanged;

// In a service provider
public function boot()
{
    Event::listen(LanguageAdded::class, function ($event) {
        // Handle new language added
        Log::info('New language added: ' . $event->language->code);
        
        // Auto-scan for new translations
        dispatch(new ScanTranslationStrings($event->language));
    });

    Event::listen(LanguageDeleted::class, function ($event) {
        // Handle language deleted
        Log::info('Language deleted: ' . $event->language->code);
        
        // Clean up related translations
        Translation::where('code', $event->language->code)->delete();
    });
}
```

### Custom Events

Create custom translation events:

```php
<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranslationCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $key,
        public string $language,
        public string $translation,
        public string $provider
    ) {}
}
```

## Performance Optimization

### Caching Strategies

Enable and configure caching:

```php
// config/translations.php
'use_permanent_cache' => true,

// Custom cache configuration
'cache' => [
    'driver' => 'redis',
    'prefix' => 'translations',
    'ttl' => 3600, // 1 hour
],
```

### Queue Configuration

Optimize queue processing:

```php
// config/queue.php
'connections' => [
    'translations' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'translations',
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

### Batch Processing

Process translations in batches:

```php
use Backstage\Translations\Laravel\Models\Translation;
use Backstage\Translations\Laravel\Jobs\TranslateKeys;

// Process translations in chunks
Translation::whereNull('translated_at')
    ->chunk(100, function ($translations) {
        TranslateKeys::dispatch($translations);
    });
```

## Custom Commands

### Creating Custom Commands

Create custom translation commands:

```php
<?php

namespace App\Console\Commands;

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Console\Command;

class TranslationStats extends Command
{
    protected $signature = 'translations:stats {--language= : Show stats for specific language}';
    protected $description = 'Show translation statistics';

    public function handle()
    {
        $languageCode = $this->option('language');
        
        if ($languageCode) {
            $this->showLanguageStats($languageCode);
        } else {
            $this->showOverallStats();
        }
    }

    private function showOverallStats()
    {
        $languages = Language::active()->count();
        $translations = Translation::count();
        $translated = Translation::whereNotNull('translated_at')->count();
        $pending = $translations - $translated;

        $this->info("Overall Statistics:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Active Languages', $languages],
                ['Total Translations', $translations],
                ['Translated', $translated],
                ['Pending', $pending],
                ['Completion Rate', round(($translated / $translations) * 100, 2) . '%'],
            ]
        );
    }

    private function showLanguageStats(string $languageCode)
    {
        $language = Language::where('code', $languageCode)->first();
        
        if (!$language) {
            $this->error("Language '{$languageCode}' not found.");
            return;
        }

        $total = Translation::where('code', $languageCode)->count();
        $translated = Translation::where('code', $languageCode)
            ->whereNotNull('translated_at')
            ->count();
        $pending = $total - $translated;

        $this->info("Statistics for {$language->name} ({$languageCode}):");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Translations', $total],
                ['Translated', $translated],
                ['Pending', $pending],
                ['Completion Rate', round(($translated / $total) * 100, 2) . '%'],
            ]
        );
    }
}
```

## Testing

### Testing Translations

Create tests for your translation functionality:

```php
<?php

namespace Tests\Feature;

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    public function test_can_add_language()
    {
        $this->artisan('translations:languages:add', [
            'locale' => 'es',
            'label' => 'Spanish'
        ])->assertExitCode(0);

        $this->assertDatabaseHas('languages', [
            'code' => 'es',
            'name' => 'Spanish'
        ]);
    }

    public function test_can_scan_translations()
    {
        // Create a language first
        Language::create([
            'code' => 'en',
            'name' => 'English',
            'native' => 'English',
            'active' => true,
        ]);

        $this->artisan('translations:scan')
            ->assertExitCode(0);

        // Check if translations were created
        $this->assertTrue(Translation::count() > 0);
    }

    public function test_model_translation()
    {
        $post = Post::create([
            'title' => 'Hello World',
            'content' => 'This is a test post',
        ]);

        // Add Spanish language
        Language::create([
            'code' => 'es',
            'name' => 'Spanish',
            'native' => 'Español',
            'active' => true,
        ]);

        // Translate attributes
        $post->translateAttributes('es');

        // Check if translations were created
        $this->assertTrue($post->translatableAttributes()->count() > 0);
    }
}
```

## Monitoring and Analytics

### Translation Analytics

Track translation usage and performance:

```php
<?php

namespace App\Services;

use Backstage\Translations\Laravel\Events\LanguageAdded;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class TranslationAnalytics
{
    public function boot()
    {
        Event::listen(LanguageAdded::class, function ($event) {
            $this->trackLanguageAdded($event->language);
        });
    }

    public function getTranslationStats(): array
    {
        return [
            'total_translations' => Translation::count(),
            'translated_count' => Translation::whereNotNull('translated_at')->count(),
            'pending_count' => Translation::whereNull('translated_at')->count(),
            'languages_count' => Language::active()->count(),
            'completion_rate' => $this->getCompletionRate(),
        ];
    }

    public function getLanguageStats(string $languageCode): array
    {
        $translations = Translation::where('code', $languageCode);
        
        return [
            'total' => $translations->count(),
            'translated' => $translations->whereNotNull('translated_at')->count(),
            'pending' => $translations->whereNull('translated_at')->count(),
        ];
    }

    private function getCompletionRate(): float
    {
        $total = Translation::count();
        $translated = Translation::whereNotNull('translated_at')->count();
        
        return $total > 0 ? round(($translated / $total) * 100, 2) : 0;
    }

    private function trackLanguageAdded(Language $language): void
    {
        Log::info('Language added', [
            'code' => $language->code,
            'name' => $language->name,
            'timestamp' => now(),
        ]);
    }
}
```

## Integration Examples

### Multi-tenant Applications

Handle translations in multi-tenant applications:

```php
<?php

namespace App\Models;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;

class TenantPost extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes;

    protected $fillable = ['title', 'content', 'tenant_id'];

    public function getTranslatableAttributes(): array
    {
        return ['title', 'content'];
    }

    // Override to include tenant context
    public function translateAttribute(mixed $attribute, string $targetLanguage, bool $overwrite = false, ?string $extraPrompt = null): mixed
    {
        $tenantContext = "This content belongs to tenant: {$this->tenant_id}";
        $prompt = $extraPrompt ? "{$tenantContext}. {$extraPrompt}" : $tenantContext;

        return parent::translateAttribute($attribute, $targetLanguage, $overwrite, $prompt);
    }
}
```

## Translating Array Values

When your translation payload contains arrays, you can declare rules to translate array values. Use `'*'` to indicate that all items under a given key should be translated, or scope it to a particular nested key.

```php
// Translate all items under the "data" key
return [
    'data' => ['*'],
];
```

```php
// Translate all items under a specific nested key within "data"
return [
    'data' => [
        'special-key' => ['*'],
    ],
];
```

This rule is nested under the `data` key. Array rules are hierarchical and mirror your array structure, so `special-key` here is resolved as `data.special-key`.

### Per-attribute rules and defaults

For model attribute translation, you can define rules per attribute by adding a method on your model named `getTranslatableAttributeRulesFor{Attribute}`. If this method does not exist, the default rule is `'*'` (translate the entire attribute).

```php
// Example on a model using content attribute:

public function getTranslatableAttributeRulesForContent(): array|string
{
    return [
        'data' => [
            'special-key' => ['*'],
        ],
    ];
}

// If the method is absent, it behaves as if:
// return '*';
```

#### Example use case

```php
public function getTranslatableAttributeRulesForContent(): array|string
{
    return [
        'metadata' => [
            'tags' => ['*'],
        ],
    ];
}

// Effect:
// - Translates all items in content.metadata.tags [0..*]
// - Leaves content.metadata.{other} unchanged
```

## Next Steps

- [Configuration](configuration.md) - Complete configuration options
