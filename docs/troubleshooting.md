# Troubleshooting

This guide helps you resolve common issues with the Laravel Translations package.

## Common Issues

### Installation Issues

#### Package Not Found
**Error**: `Could not find package backstage/laravel-translations`

**Solution**:
```bash
# Clear Composer cache
composer clear-cache

# Update Composer
composer update

# Install package
composer require backstage/laravel-translations
```

#### Service Provider Not Registered
**Error**: Commands not found or package not working

**Solution**:
1. Check if package is in `composer.json`
2. Run `composer dump-autoload`
3. Clear config cache: `php artisan config:clear`
4. Check if providers are registered in `config/app.php`

### Migration Issues

#### Migration Fails
**Error**: `SQLSTATE[42000]: Syntax error or access violation`

**Solutions**:
1. **Check database connection**:
   ```bash
   php artisan migrate:status
   ```

2. **Check table exists**:
   ```sql
   SHOW TABLES LIKE 'languages';
   ```

3. **Run migrations manually**:
   ```bash
   php artisan migrate --force
   ```

#### Table Already Exists
**Error**: `Table 'languages' already exists`

**Solution**:
```bash
# Check if tables exist
php artisan tinker
>>> Schema::hasTable('languages')

# If they exist, skip migrations
php artisan migrate --pretend
```

### Configuration Issues

#### Invalid Driver
**Error**: `Driver [invalid-driver] not supported`

**Solution**:
1. Check `config/translations.php`:
   ```php
   'default' => 'google-translate', // Valid drivers: google-translate, deep-l, ai
   ```

2. Check environment variables:
   ```bash
   php artisan config:show translations.translators.default
   ```

#### Missing API Keys
**Error**: `API key not configured`

**Solutions**:
- **DeepL**: Set `DEEPL_API_KEY` in `.env`
- **AI**: Set `OPENAI_API_KEY` in `.env`
- **Google Translate**: No API key needed

### Translation Issues

#### Translations Not Found
**Error**: `Translation not found` or empty translations

**Solutions**:
1. **Run scan first**:
   ```bash
   php artisan translations:scan
   ```

2. **Check if languages exist**:
   ```bash
   php artisan tinker
   >>> \Backstage\Translations\Laravel\Models\Language::count()
   ```

3. **Add languages**:
   ```bash
   php artisan translations:languages:add en English
   ```

#### Translation Quality Issues
**Problem**: Poor translation quality

**Solutions**:
1. **Switch providers**:
   ```php
   // config/translations.php
   'default' => 'deep-l', // Better quality than Google Translate
   ```

2. **Use AI with custom prompts**:
   ```php
   'ai' => [
       'system_prompt' => 'You are a professional translator...',
   ],
   ```

3. **Add context to translations**:
   ```php
   $post->translateAttribute('title', 'es', false, 'This is a blog post title about technology');
   ```

### Model Attribute Issues

#### Model Not Translating
**Problem**: Model attributes not being translated automatically

**Solutions**:
1. **Check model registration**:
   ```php
   // config/translations.php
   'eloquent' => [
       'translatable-models' => [
           App\Models\Post::class, // Make sure your model is here
       ],
   ],
   ```

2. **Check model implementation**:
   ```php
   class Post extends Model implements TranslatesAttributes
   {
       use HasTranslatableAttributes;
       
       public function getTranslatableAttributes(): array
       {
           return ['title', 'content']; // Make sure attributes are listed
       }
   }
   ```

3. **Check casts**:
   ```php
   protected $casts = [
       'title' => 'string',
       'content' => 'string',
   ];
   ```

#### Translations Not Syncing
**Problem**: Model translations not syncing

**Solutions**:
1. **Run sync command**:
   ```bash
   php artisan translations:sync
   ```

2. **Check model events**:
   ```php
   // Make sure model implements the contract
   class Post extends Model implements TranslatesAttributes
   ```

3. **Check queue**:
   ```bash
   php artisan queue:work
   ```

### Performance Issues

#### Slow Translation
**Problem**: Translations taking too long

**Solutions**:
1. **Enable caching**:
   ```php
   // config/translations.php
   'use_permanent_cache' => true,
   ```

2. **Use faster provider**:
   ```php
   'default' => 'google-translate', // Faster than DeepL
   ```

3. **Optimize scan paths**:
   ```php
   'paths' => [
       base_path('app'), // Only scan necessary directories
       base_path('resources/views'),
   ],
   ```

#### Memory Issues
**Error**: `Allowed memory size exhausted`

**Solutions**:
1. **Increase memory limit**:
   ```php
   ini_set('memory_limit', '512M');
   ```

2. **Process in chunks**:
   ```bash
   php artisan translations:translate --code=es
   ```

3. **Use queue**:
   ```bash
   php artisan queue:work
   ```

### Command Issues

#### Command Not Found
**Error**: `Command "translations:scan" is not defined`

**Solutions**:
1. **Clear command cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Check service provider**:
   ```php
   // config/app.php
   'providers' => [
       Backstage\Translations\Laravel\TranslationServiceProvider::class,
   ],
   ```

3. **Reinstall package**:
   ```bash
   composer remove backstage/laravel-translations
   composer require backstage/laravel-translations
   ```

#### Command Fails
**Error**: Command exits with error

**Solutions**:
1. **Check logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Run with verbose output**:
   ```bash
   php artisan translations:scan -v
   ```

3. **Check permissions**:
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

## Debugging

### Enable Debug Mode

```env
# .env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Check Package Status

```bash
# Check if package is installed
composer show backstage/laravel-translations

# Check if commands are registered
php artisan list | grep translation

# Check if models exist
php artisan tinker
>>> \Backstage\Translations\Laravel\Models\Language::count()
```

### Check Configuration

```bash
# View configuration
php artisan config:show translations

# Check specific setting
php artisan config:show translations.translators.default
```

### Check Database

```bash
# Check if tables exist
php artisan tinker
>>> Schema::hasTable('languages')
>>> Schema::hasTable('translations')
>>> Schema::hasTable('translated_attributes')

# Check data
>>> \Backstage\Translations\Laravel\Models\Language::all()
>>> \Backstage\Translations\Laravel\Models\Translation::count()
```

### Check Translations

```bash
# Test translation retrieval
php artisan tinker
>>> app('translator')->get('messages.welcome', [], 'es')

# Check if translations exist
>>> \Backstage\Translations\Laravel\Models\Translation::where('key', 'welcome')->get()
```

## Logging

### Enable Translation Logging

```php
// In a service provider
use Backstage\Translations\Laravel\Events\TranslationCompleted;

Event::listen(TranslationCompleted::class, function ($event) {
    Log::info('Translation completed', [
        'key' => $event->translation->key,
        'language' => $event->translation->code,
        'provider' => $event->provider,
    ]);
});
```

### Check Logs

```bash
# View recent logs
tail -f storage/logs/laravel.log

# Search for translation logs
grep -i translation storage/logs/laravel.log

# Check specific date
grep "2024-01-01" storage/logs/laravel.log | grep translation
```

## Performance Monitoring

### Monitor Translation Performance

```php
// Add to your service provider
use Backstage\Translations\Laravel\Events\TranslationCompleted;

Event::listen(TranslationCompleted::class, function ($event) {
    $duration = microtime(true) - $event->startTime;
    
    if ($duration > 5) { // Log slow translations
        Log::warning('Slow translation detected', [
            'key' => $event->translation->key,
            'duration' => $duration,
            'provider' => $event->provider,
        ]);
    }
});
```

### Monitor API Usage

```php
// Track API usage
use Backstage\Translations\Laravel\Events\TranslationCompleted;

Event::listen(TranslationCompleted::class, function ($event) {
    $characters = strlen($event->translation->text);
    
    // Log to analytics service
    Analytics::track('translation_api_usage', [
        'provider' => $event->provider,
        'characters' => $characters,
        'cost' => $this->calculateCost($characters, $event->provider),
    ]);
});
```

## Getting Help

### Check Documentation

1. [Installation Guide](installation.md)
2. [Configuration Reference](configuration.md)
3. [API Reference](api-reference.md)

### Common Solutions

1. **Clear all caches**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Reinstall package**:
   ```bash
   composer remove backstage/laravel-translations
   composer require backstage/laravel-translations
   php artisan vendor:publish --provider="Backstage\Translations\Laravel\TranslationServiceProvider"
   php artisan migrate
   ```

3. **Check Laravel version compatibility**:
   ```bash
   php artisan --version
   composer show laravel/framework
   ```

### Report Issues

If you're still experiencing issues:

1. **Check existing issues**: [GitHub Issues](https://github.com/backstagephp/laravel-translations/issues)
2. **Create new issue** with:
   - Laravel version
   - PHP version
   - Package version
   - Error messages
   - Steps to reproduce

### Community Support

- **GitHub Discussions**: [Ask questions](https://github.com/backstagephp/laravel-translations/discussions)
- **Laravel Community**: [Laravel Discord](https://discord.gg/laravel)
- **Stack Overflow**: Tag questions with `laravel-translations`

## Prevention

### Best Practices

1. **Always run migrations** after installing
2. **Test in development** before production
3. **Monitor API usage** to avoid rate limits
4. **Keep package updated** for bug fixes
5. **Use caching** for better performance

### Regular Maintenance

```bash
# Weekly maintenance script
#!/bin/bash

# Clear caches
php artisan config:clear
php artisan cache:clear

# Sync translations
php artisan translations:sync

# Check for updates
composer outdated backstage/laravel-translations

echo "Maintenance completed successfully!"
```

## Next Steps

- [Installation](installation.md) - Fresh installation guide
- [Configuration](configuration.md) - Complete configuration options
- [Basic Usage](basic-usage.md) - Core features and usage
