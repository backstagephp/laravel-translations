<?php

use Backstage\Translations\Laravel\TranslationLoaderServiceProvider;
use Backstage\Translations\Laravel\Base\TranslationLoader;

it('registers translation loader', function () {
    $app = app();
    $provider = new TranslationLoaderServiceProvider($app);
    $provider->register();

    $loader = $app->make('translation.loader');

    expect($loader)->toBeInstanceOf(TranslationLoader::class);
});

it('provides translation loader service', function () {
    $provider = new TranslationLoaderServiceProvider(app());

    $services = $provider->provides();

    expect($services)->toContain('translation.loader');
});

it('is a deferrable provider', function () {
    $provider = new TranslationLoaderServiceProvider(app());

    expect($provider)->toBeInstanceOf(\Illuminate\Contracts\Support\DeferrableProvider::class);
});

