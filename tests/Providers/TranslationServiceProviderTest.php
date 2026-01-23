<?php

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use Backstage\Translations\Laravel\TranslationServiceProvider;
use Illuminate\Support\Facades\Artisan;

it('registers TranslatorContract as singleton', function () {
    $translator1 = app(TranslatorContract::class);
    $translator2 = app(TranslatorContract::class);

    expect($translator1)->toBe($translator2);
});

it('registers all commands', function () {
    expect(Artisan::all())->toHaveKey('translations:scan')
        ->and(Artisan::all())->toHaveKey('translations:translate')
        ->and(Artisan::all())->toHaveKey('translations:sync')
        ->and(Artisan::all())->toHaveKey('translations:languages:add');
});

it('registers event listeners', function () {
    $language = \Backstage\Translations\Laravel\Models\Language::create(['code' => 'en', 'name' => 'English']);

    expect($language)->not->toBeNull();

    $language->delete();

    expect(\Backstage\Translations\Laravel\Models\Translation::where('code', 'en')->count())->toBe(0);
});

it('registers cache when use_permanent_cache is enabled', function () {
    config()->set('translations.use_permanent_cache', true);

    $provider = new TranslationServiceProvider(app());
    $provider->bootingPackage();

    expect(config('translations.use_permanent_cache'))->toBeTrue();
});
