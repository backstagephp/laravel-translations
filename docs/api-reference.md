# API Reference

Complete API reference for the Laravel Translations package.

## Models

### Language Model

The `Language` model represents a language in your application.

```php
use Backstage\Translations\Laravel\Models\Language;
```

#### Properties

| Property | Type | Description |
|----------|------|-------------|
| `code` | string | Language code (e.g., 'en', 'es', 'fr-BE') |
| `name` | string | Display name in your app's locale |
| `native` | string | Native name of the language |
| `active` | boolean | Whether the language is active |
| `default` | boolean | Whether it's the default language |

#### Methods

##### `scopeActive($query)`
Get only active languages.

```php
$activeLanguages = Language::active()->get();
```

##### `default()`
Get the default language.

```php
$default = Language::default();
```

##### `getLanguageCodeAttribute()`
Get the language code (without country).

```php
$language = Language::where('code', 'en-US')->first();
echo $language->language_code; // 'en'
```

##### `getCountryCodeAttribute()`
Get the country code.

```php
$language = Language::where('code', 'en-US')->first();
echo $language->country_code; // 'US'
```

##### `getLocalizedCountryNameAttribute($locale = null)`
Get localized country name.

```php
$language = Language::where('code', 'en-US')->first();
echo $language->localized_country_name; // 'United States'
echo $language->getLocalizedCountryNameAttribute('es'); // 'Estados Unidos'
```

##### `getLocalizedLanguageNameAttribute($locale = null)`
Get localized language name.

```php
$language = Language::where('code', 'en-US')->first();
echo $language->localized_language_name; // 'English'
echo $language->getLocalizedLanguageNameAttribute('es'); // 'Inglés'
```

#### Relationships

##### `translations()`
Has many translations.

```php
$language = Language::find('es');
$translations = $language->translations;
```

##### `translatableAttributes()`
Has many translated attributes.

```php
$language = Language::find('es');
$attributes = $language->translatableAttributes;
```

### Translation Model

The `Translation` model represents a translation string.

```php
use Backstage\Translations\Laravel\Models\Translation;
```

#### Properties

| Property | Type | Description |
|----------|------|-------------|
| `code` | string | Language code |
| `group` | string | Translation group (e.g., 'messages') |
| `key` | string | Translation key |
| `text` | string | Translated text |
| `source_text` | string | Original source text |
| `namespace` | string | Translation namespace |
| `translated_at` | datetime | When it was translated |

#### Methods

##### `getLanguageCodeAttribute()`
Get language code without country.

```php
$translation = Translation::find(1);
echo $translation->language_code; // 'en'
```

##### `getCountryCodeAttribute()`
Get country code.

```php
$translation = Translation::find(1);
echo $translation->country_code; // 'US'
```

#### Relationships

##### `language()`
Belongs to language.

```php
$translation = Translation::find(1);
$language = $translation->language;
```

### TranslatedAttribute Model

The `TranslatedAttribute` model represents a translated model attribute.

```php
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
```

#### Properties

| Property | Type | Description |
|----------|------|-------------|
| `code` | string | Language code |
| `translatable_type` | string | Model class name |
| `translatable_id` | integer | Model ID |
| `attribute` | string | Attribute name |
| `translated_attribute` | text | Translated value |
| `translated_at` | datetime | When it was translated |

#### Methods

##### `translatable()`
Morph to translatable model.

```php
$translatedAttr = TranslatedAttribute::find(1);
$model = $translatedAttr->translatable; // Post, Product, etc.
```

##### `language()`
Belongs to language.

```php
$translatedAttr = TranslatedAttribute::find(1);
$language = $translatedAttr->language;
```

## Contracts

### TranslatesAttributes Contract

Interface for models that can have translated attributes.

```php
use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
```

#### Methods

##### `translateAttributes(?string $targetLanguage = null): array`
Translate all translatable attributes.

```php
$post = Post::find(1);
$translations = $post->translateAttributes('es');
// Returns: ['title' => 'Título', 'content' => 'Contenido']
```

##### `translateAttributesForAllLanguages(): array`
Translate all attributes for all languages.

```php
$post = Post::find(1);
$allTranslations = $post->translateAttributesForAllLanguages();
// Returns: ['title' => ['es' => 'Título', 'fr' => 'Titre'], ...]
```

##### `translateAttributeForAllLanguages(string $attribute, bool $overwrite = false): array`
Translate specific attribute for all languages.

```php
$post = Post::find(1);
$titleTranslations = $post->translateAttributeForAllLanguages('title');
// Returns: ['es' => 'Título', 'fr' => 'Titre']
```

##### `translateAttribute(mixed $attribute, string $targetLanguage, bool $overwrite = false, ?string $extraPrompt = null): mixed`
Translate specific attribute.

```php
$post = Post::find(1);
$translatedTitle = $post->translateAttribute('title', 'es');
// Returns: 'Título'
```

##### `pushTranslateAttribute(string $attribute, string $translation, string $locale): void`
Store manual translation.

```php
$post = Post::find(1);
$post->pushTranslateAttribute('title', 'Mi Título', 'es');
```

##### `getTranslatedAttribute(string $attribute, ?string $locale = null): mixed`
Get translated attribute.

```php
$post = Post::find(1);
$title = $post->getTranslatedAttribute('title', 'es');
// Returns: 'Título'
```

##### `getTranslatedAttributes(?string $locale = null): array`
Get all translated attributes.

```php
$post = Post::find(1);
$translations = $post->getTranslatedAttributes('es');
// Returns: ['title' => 'Título', 'content' => 'Contenido']
```

##### `getTranslatableAttributes(): array`
Get list of translatable attributes.

```php
$post = Post::find(1);
$attributes = $post->getTranslatableAttributes();
// Returns: ['title', 'content', 'excerpt']
```

##### `isTranslatableAttribute(string $attribute): bool`
Check if attribute is translatable.

```php
$post = Post::find(1);
$isTranslatable = $post->isTranslatableAttribute('title');
// Returns: true
```

##### `translatableAttributes(): MorphMany`
Get translated attributes relationship.

```php
$post = Post::find(1);
$translations = $post->translatableAttributes;
```

##### `syncTranslations(?OutputStyle $output = null): void`
Sync translations.

```php
$post = Post::find(1);
$post->syncTranslations();
```

##### `updateTranslateAttributes(array $attributes): void`
Update multiple attributes.

```php
$post = Post::find(1);
$post->updateTranslateAttributes(['title' => 'New Title', 'content' => 'New Content']);
```

##### `updateAttributesIfTranslatable(array $translatableAttributes): void`
Update only translatable attributes.

```php
$post = Post::find(1);
$post->updateAttributesIfTranslatable(['title', 'content']);
```

##### `getTranslatableAttributeRulesFor(string $attribute): array|string`
Get rules for specific attribute.

```php
$post = Post::find(1);
$rules = $post->getTranslatableAttributeRulesFor('title');
// Returns: ['max_length' => 100, 'preserve_formatting' => true]
```

### TranslatorContract

Interface for translation providers.

```php
use Backstage\Translations\Laravel\Contracts\TranslatorContract;
```

#### Methods

##### `translate(string $text, string $targetLanguage, string $sourceLanguage = 'en'): string`
Translate text.

```php
$translator = app(TranslatorContract::class);
$translation = $translator->translate('Hello', 'es', 'en');
// Returns: 'Hola'
```

## Traits

### HasTranslatableAttributes Trait

**Required trait** for models with translatable attributes. Models must use this trait to enable translation functionality.

```php
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;

class Post extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes; // Required trait
}
```

#### Methods

All methods from the `TranslatesAttributes` contract are available.

#### Events

The trait automatically handles these events:

- **created**: Syncs translations when model is created
- **updated**: Updates translations when translatable attributes change
- **deleting**: Deletes related translations when model is deleted

## Commands

### TranslationsScan

Scan application for translation strings.

```bash
php artisan translations:scan
```

**Options:**
- `--language={code}` - Scan for specific language

### TranslateTranslations

Translate scanned strings.

```bash
php artisan translations:translate
    {--code= : Translate for specific language}
    {--update : Update existing translations}
```

### TranslationsAddLanguage

Add new language.

```bash
php artisan translations:languages:add {locale} {label}
```

### SyncTranslations

Sync translations.

```bash
php artisan translations:sync
```

## Jobs

### ScanTranslationStrings

Job for scanning translation strings.

```php
use Backstage\Translations\Laravel\Jobs\ScanTranslationStrings;

// Dispatch job
ScanTranslationStrings::dispatch();

// Dispatch for specific language
ScanTranslationStrings::dispatch($language);

// Dispatch with redo flag
ScanTranslationStrings::dispatch(null, true);
```

### TranslateKeys

Job for translating keys.

```php
use Backstage\Translations\Laravel\Jobs\TranslateKeys;

// Dispatch job
TranslateKeys::dispatch();

// Dispatch for specific language
TranslateKeys::dispatch($language);
```

## Events

### LanguageAdded

Fired when a new language is added.

```php
use Backstage\Translations\Laravel\Events\LanguageAdded;

Event::listen(LanguageAdded::class, function ($event) {
    // Handle language added
    $language = $event->language;
});
```

### LanguageDeleted

Fired when a language is deleted.

```php
use Backstage\Translations\Laravel\Events\LanguageDeleted;

Event::listen(LanguageDeleted::class, function ($event) {
    // Handle language deleted
    $language = $event->language;
});
```

### LanguageCodeChanged

Fired when a language code is changed.

```php
use Backstage\Translations\Laravel\Events\LanguageCodeChanged;

Event::listen(LanguageCodeChanged::class, function ($event) {
    // Handle language code changed
    $oldCode = $event->oldCode;
    $newCode = $event->newCode;
});
```

## Helpers

### Global Helper Functions

#### `localized_language_name(string $locale): string`
Get localized language name.

```php
$name = localized_language_name('es');
// Returns: 'Spanish'
```

#### `localized_country_name(string $countryCode, ?string $locale = null): string`
Get localized country name.

```php
$name = localized_country_name('US');
// Returns: 'United States'
```

## Configuration

### Translation Configuration

```php
// config/translations.php
return [
    'scan' => [
        'paths' => [base_path()],
        'extensions' => ['*.php', '*.blade.php'],
        'functions' => ['trans', '__', '@lang'],
    ],
    'use_permanent_cache' => false,
    'eloquent' => [
        'translatable-models' => [],
    ],
    'translators' => [
        'default' => 'google-translate',
        'drivers' => [
            'google-translate' => [],
            'deep-l' => [
                'options' => [
                    TranslatorOptions::SERVER_URL => env('DEEPL_SERVER_URL'),
                ],
            ],
            'ai' => [
                'provider' => Provider::OpenAI,
                'model' => 'gpt-4.1',
                'system_prompt' => '...',
            ],
        ],
    ],
];
```

## Service Providers

### TranslationServiceProvider

Main service provider for the package.

```php
use Backstage\Translations\Laravel\TranslationServiceProvider;

// Register in config/app.php
'providers' => [
    // ...
    TranslationServiceProvider::class,
];
```

### TranslationLoaderServiceProvider

Service provider for translation loading.

```php
use Backstage\Translations\Laravel\TranslationLoaderServiceProvider;

// Register in config/app.php
'providers' => [
    // ...
    TranslationLoaderServiceProvider::class,
];
```

## Facades

### Translator Facade

Facade for translation operations.

```php
use Backstage\Translations\Laravel\Facades\Translator;

// Translate text
$translation = Translator::translate('Hello', 'es');

// Use specific driver
$translation = Translator::with('deep-l')->translate('Hello', 'es');
```

## Caching

### TranslationStringsCache

Cache for translation strings.

```php
use Backstage\Translations\Laravel\Caches\TranslationStringsCache;

// Update cache
TranslationStringsCache::update();

// Clear cache
TranslationStringsCache::clear();
```

## Next Steps

- [Advanced Usage](advanced-usage.md) - Advanced features and customization
- [Troubleshooting](troubleshooting.md) - Common issues and solutions
- [Configuration](configuration.md) - Complete configuration options
