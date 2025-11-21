<?php

use Backstage\Translations\Laravel\Managers\TranslatorManager;
use Backstage\Translations\Laravel\Drivers\GoogleTranslator;
use Backstage\Translations\Laravel\Drivers\AITranslator;
use Backstage\Translations\Laravel\Drivers\DeepLTranslator;
use Illuminate\Support\Facades\Config;

it('returns default driver from config', function () {
    Config::set('translations.translators.default', 'google-translate');

    $manager = new TranslatorManager(app());

    expect($manager->getDefaultDriver())->toBe('google-translate');
});

it('can switch to different driver using with method', function () {
    $manager = new TranslatorManager(app());

    $driver = $manager->with('ai');

    expect($driver)->toBeInstanceOf(AITranslator::class);
});

it('creates Google Translate driver', function () {
    $manager = new TranslatorManager(app());

    $driver = $manager->driver('google-translate');

    expect($driver)->toBeInstanceOf(GoogleTranslator::class);
});

it('creates AI driver', function () {
    $manager = new TranslatorManager(app());

    $driver = $manager->driver('ai');

    expect($driver)->toBeInstanceOf(AITranslator::class);
});

it('creates DeepL driver', function () {
    $manager = new TranslatorManager(app());

    $driver = $manager->driver('deep-l');

    expect($driver)->toBeInstanceOf(DeepLTranslator::class);
});

