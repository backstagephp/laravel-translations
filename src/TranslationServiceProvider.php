<?php

namespace Backstage\Translations\Laravel;

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
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TranslationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-translations')
            ->hasMigrations(
                '2025_02_15_345600_create_languages_table',
                '2025_02_15_345601_create_translations_table'
            )
            ->hasConfigFile('translations')
            ->hasCommands(
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
    }
}
