<?php

namespace Backstage\Translations\Laravel;

use Backstage\Translations\Laravel\Base\Translator;
use Backstage\Translations\Laravel\Commands\TranslateTranslations;
use Backstage\Translations\Laravel\Commands\TranslationsAddLanguage;
use Backstage\Translations\Laravel\Commands\TranslationsScan;
use Backstage\Translations\Laravel\Base\TranslationLoader;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TranslationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-translations')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations(
                'create_laravel_languages',
                'create_laravel_translations_table'
            )
            ->hasCommands(
                TranslateTranslations::class,
                TranslationsAddLanguage::class,
                TranslationsScan::class,
            );
    }

    public function registeringPackage()
    {
        $this->app->register(\Spatie\TranslationLoader\TranslationServiceProvider::class, true);

        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);
            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });

    }
}
