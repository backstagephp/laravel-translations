<?php

namespace Backstage\Translations\Laravel;

use Backstage\PermanentCache\Laravel\Facades\PermanentCache;
use Backstage\Translations\Laravel\Caches\TranslationStringsCache;
use Backstage\Translations\Laravel\Commands\SyncTranslations;
use Backstage\Translations\Laravel\Commands\TranslateTranslations;
use Backstage\Translations\Laravel\Commands\TranslationsAddLanguage;
use Backstage\Translations\Laravel\Commands\TranslationsScan;
use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Listners\DeleteTranslations;
use Backstage\Translations\Laravel\Listners\HandleLanguageCodeChanges;
use Backstage\Translations\Laravel\Managers\TranslatorManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schedule;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TranslationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-translations')
            ->hasMigrations(
                'create_languages_table',
                'create_translations_table',
                'create_translated_attributes_table',
            )
            ->hasConfigFile('translations')
            ->hasCommands(
                SyncTranslations::class,
                TranslateTranslations::class,
                TranslationsAddLanguage::class,
                TranslationsScan::class,
            );
    }

    public function registeringPackage()
    {
        $this->app->singleton(TranslatorContract::class, fn ($app) => new TranslatorManager($app));
    }

    public function bootingPackage()
    {
        Event::listen(LanguageDeleted::class, DeleteTranslations::class);
        Event::listen(LanguageCodeChanged::class, HandleLanguageCodeChanges::class);

        if (config('translations.use_permanent_cache')) {
            PermanentCache::caches([
                TranslationStringsCache::class,
            ]);
        }

        Schedule::command(SyncTranslations::class)
            ->dailyAt('00:00')
            ->withoutOverlapping();
    }
}
