<?php

namespace Backstage\Translations\Laravel;

use Backstage\Translations\Laravel\Base\TranslationLoader;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationLoaderServiceProvider extends IlluminateTranslationServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton('translation.loader', function (Application $app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['translation.loader'];
    }
}
