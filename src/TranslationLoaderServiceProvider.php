<?php

namespace Backstage\Translations\Laravel;

use Backstage\Translations\Laravel\Base\Translator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Backstage\Translations\Laravel\Base\TranslationLoader;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationLoaderServiceProvider extends IlluminateTranslationServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app->getLocale();

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['translator', 'translation.loader'];
    }
}
