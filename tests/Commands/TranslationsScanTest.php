<?php

use Backstage\Translations\Laravel\Commands\TranslationsScan;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Jobs\ScanTranslationStrings;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;

it('scans translations when languages exist', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Artisan::call(TranslationsScan::class);

    expect(Artisan::output())->toBeString();
});

it('creates default language when no languages exist', function () {
    Queue::fake();

    config()->set('app.locale', 'en');

    Artisan::call(TranslationsScan::class);

    expect(Language::count())->toBe(1)
        ->and(Language::first()->code)->toBe('en');
});

it('dispatches scan job for each active language', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);
    Language::create(['code' => 'de', 'name' => 'German', 'active' => false]);

    Artisan::call(TranslationsScan::class);

    expect(Artisan::output())->toBeString();
});

