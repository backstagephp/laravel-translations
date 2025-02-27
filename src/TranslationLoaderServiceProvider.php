<?php

namespace Backstage\Translations\Laravel;

use Backstage\Translations\Laravel\Base\TranslationLoader;
use Backstage\Translations\Laravel\Base\Translator;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationLoaderServiceProvider extends IlluminateTranslationServiceProvider
{
    public function register()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], [__DIR__.'/lang', $app['path.lang']]);
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
