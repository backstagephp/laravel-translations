# Model Attributes Translation

The Laravel Translations package provides powerful functionality to automatically translate Eloquent model attributes. This feature allows you to store content in one language and automatically generate translations for other languages.

## Overview

Model attribute translation works by:

1. **Detecting changes** to translatable attributes
2. **Queuing translation jobs** automatically
3. **Storing translations** in a separate table
4. **Retrieving translations** based on current locale

## Setup

### 1. Configure Translatable Models

Add your models to the configuration:

```php
// config/translations.php
'eloquent' => [
    'translatable-models' => [
        App\Models\Post::class,
        App\Models\Product::class,
        App\Models\Category::class,
    ],
],
```

### 2. Implement the Contract and Use the Trait

Your model must implement the `TranslatesAttributes` contract AND use the `HasTranslatableAttributes` trait:

```php
<?php

namespace App\Models;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes; // Required trait for translatable attributes

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'meta_description',
    ];

    // Define which attributes should be translated
    public function getTranslatableAttributes(): array
    {
        return [
            'title',
            'content',
            'excerpt',
            'meta_description',
        ];
    }

    // Define casts for translatable attributes
    protected $casts = [
        'title' => 'string',
        'content' => 'string',
        'excerpt' => 'string',
        'meta_description' => 'string',
    ];
}
```

### 3. Database Migration

The package creates a `translated_attributes` table to store translations:

```php
// Migration: create_translated_attributes_table.php
Schema::create('translated_attributes', function (Blueprint $table) {
    $table->id();
    $table->string('code'); // Language code
    $table->morphs('translatable'); // Polymorphic relationship
    $table->string('attribute'); // Attribute name
    $table->text('translated_attribute'); // Translated value
    $table->timestamp('translated_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

## Basic Usage

### Creating and Updating Models

When you create or update a model with translatable attributes, translations are automatically queued:

```php
// Create a new post
$post = Post::create([
    'title' => 'Hello World',
    'content' => 'This is my first post',
    'excerpt' => 'A short description',
]);

// Translations are automatically queued for all active languages
// No additional code needed!
```

### Retrieving Translations

Get translated attributes for the current locale:

```php
$post = Post::find(1);

// Get translated title for current locale
$title = $post->getTranslatedAttribute('title');

// Get translated title for specific locale
$spanishTitle = $post->getTranslatedAttribute('title', 'es');

// Get all translated attributes for current locale
$translations = $post->getTranslatedAttributes();

// Get all translated attributes for specific locale
$spanishTranslations = $post->getTranslatedAttributes('es');
```

### Manual Translation

Translate attributes manually:

```php
$post = Post::find(1);

// Translate single attribute
$translatedTitle = $post->translateAttribute('title', 'es');

// Translate all attributes for specific language
$translations = $post->translateAttributes('es');

// Translate all attributes for all languages
$allTranslations = $post->translateAttributesForAllLanguages();
```

## Advanced Features

### Overwriting Existing Translations

Force translation of existing content:

```php
$post = Post::find(1);

// Overwrite existing Spanish translation
$post->translateAttribute('title', 'es', overwrite: true);

// Overwrite all translations for this attribute
$post->translateAttributeForAllLanguages('title', overwrite: true);
```

### Custom Translation Prompts

Add custom context for better translations:

```php
$post = Post::find(1);

// Translate with custom prompt
$translatedTitle = $post->translateAttribute(
    'title', 
    'es', 
    overwrite: false,
    extraPrompt: 'This is a blog post title about technology'
);
```

### Pushing Manual Translations

Store translations manually:

```php
$post = Post::find(1);

// Store a manual translation
$post->pushTranslateAttribute('title', 'Hola Mundo', 'es');

// This bypasses automatic translation
```

## Synchronization

### Automatic Sync

The package automatically syncs translations when:

- A model is created
- Translatable attributes are updated
- Languages are added or removed

### Manual Sync

Force synchronization:

```php
$post = Post::find(1);

// Sync all translations for this model
$post->syncTranslations();

// Sync with progress output
$post->syncTranslations($output);
```

### Bulk Sync

Sync all models at once:

```bash
php artisan translations:sync
```

## Working with Relationships

### Eager Loading Translations

Load translations efficiently:

```php
// Load posts with their translations
$posts = Post::with('translatableAttributes')->get();

// Load specific language translations
$posts = Post::with(['translatableAttributes' => function ($query) {
    $query->where('code', 'es');
}])->get();
```

### Translation Relationships

Access translation relationships:

```php
$post = Post::find(1);

// Get all translations for this post
$translations = $post->translatableAttributes;

// Get translations for specific language
$spanishTranslations = $post->translatableAttributes()
    ->where('code', 'es')
    ->get();

// Get translations for specific attribute
$titleTranslations = $post->translatableAttributes()
    ->where('attribute', 'title')
    ->get();
```

## Performance Optimization

### Caching

Enable caching for better performance:

```php
// config/translations.php
'use_permanent_cache' => true,
```

### Queued Operations

Translation operations are automatically queued:

```php
// This happens in the background
$post->translateAttributes('es');
```

### Batch Operations

Process multiple models efficiently:

```php
// Translate multiple posts at once
$posts = Post::where('created_at', '>', now()->subDay())->get();

foreach ($posts as $post) {
    $post->translateAttributesForAllLanguages();
}
```

## Best Practices

### Choose Translatable Attributes Wisely

Only translate attributes that need translation:

```php
public function getTranslatableAttributes(): array
{
    return [
        'title',           // ✅ User-facing content
        'content',         // ✅ User-facing content
        'excerpt',         // ✅ User-facing content
        // 'id',           // ❌ System data
        // 'created_at',   // ❌ System data
        // 'user_id',      // ❌ System data
    ];
}
```

### Use Appropriate Casts

Define proper casts for translatable attributes:

```php
protected $casts = [
    'title' => 'string',
    'content' => 'string',
    'excerpt' => 'string',
    'meta_data' => 'array', // If storing JSON
];
```


## Troubleshooting

### Common Issues

**Translations not created**: Check that the model is registered in config AND uses the `HasTranslatableAttributes` trait

**Missing translations**: Run `translations:sync` to fill missing translations

**Performance issues**: Enable caching and use queued operations

**Translation errors**: Check your translation provider configuration

**Model not implementing contract**: Ensure your model implements `TranslatesAttributes` and uses `HasTranslatableAttributes` trait

### Debugging

Check translation status:

```php
$post = Post::find(1);

// Check if attribute is translatable
$isTranslatable = $post->isTranslatableAttribute('title');

// Get translation status
$translations = $post->translatableAttributes;
foreach ($translations as $translation) {
    echo "{$translation->attribute}: {$translation->code} - {$translation->translated_at}";
}
```

## Next Steps

- [Commands](commands.md) - Complete command reference
- [Advanced Usage](advanced-usage.md) - Advanced features and customization
