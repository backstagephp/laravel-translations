<?php

namespace Vormkracht10\LaravelTranslations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\LaravelTranslations\Commands\LaravelTranslationsCommand;
use Vormkracht10\LaravelTranslations\Commands\MakeLang;
use Vormkracht10\LaravelTranslations\Commands\TranslateImports;
use Vormkracht10\LaravelTranslations\Translations\TranslationLoader;
use Vormkracht10\LaravelTranslations\Translations\Translator;

class LaravelTranslationsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */

        $package
            ->name('laravel-translations')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations(
                'create_laravel_languages',
                'create_laravel_translations_table'
            )
            ->hasCommands(
                LaravelTranslationsCommand::class,
                MakeLang::class,
                TranslateImports::class
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
