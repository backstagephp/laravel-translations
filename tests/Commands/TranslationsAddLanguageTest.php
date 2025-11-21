<?php

use Backstage\Translations\Laravel\Commands\TranslationsAddLanguage;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Support\Facades\Artisan;

it('can add a new language', function () {
    Artisan::call(TranslationsAddLanguage::class, [
        'code' => 'en',
        'name' => 'English',
    ]);

    expect(Language::where('code', 'en')->exists())->toBeTrue()
        ->and(Artisan::output())->toContain('added');
});

it('prevents adding duplicate language', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $result = Artisan::call(TranslationsAddLanguage::class, [
        'code' => 'en',
        'name' => 'English',
    ]);

    expect($result)->toBe(\Illuminate\Console\Command::INVALID)
        ->and(Artisan::output())->toContain('already exists')
        ->and(Language::where('code', 'en')->count())->toBe(1);
});

it('creates language with correct attributes', function () {
    Artisan::call(TranslationsAddLanguage::class, [
        'code' => 'fr',
        'name' => 'French',
    ]);

    $language = Language::where('code', 'fr')->first();

    expect($language)->not->toBeNull()
        ->and($language->name)->toBe('French')
        ->and($language->native)->not->toBeEmpty();
});

