<?php

use Backstage\Translations\Laravel\Jobs\TranslateKeys;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Queue;

it('translates keys for all languages when no language provided', function () {
    Queue::fake();

    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => null,
    ]);

    Translation::create([
        'code' => 'fr',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => null,
    ]);

    $job = new TranslateKeys;

    expect(fn () => $job->handle())->toThrow();
})->skip('Requires translation service');

it('translates keys for specific language when provided', function () {
    $language = Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Translation::create([
        'code' => 'fr',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => null,
    ]);

    $job = new TranslateKeys($language);

    expect(fn () => $job->handle())->toThrow();
})->skip('Requires translation service');

it('only translates untranslated keys', function () {
    $language = Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $translated = Translation::create([
        'code' => 'fr',
        'group' => 'test',
        'key' => 'translated',
        'text' => 'Translated',
        'translated_at' => now(),
    ]);

    $untranslated = Translation::create([
        'code' => 'fr',
        'group' => 'test',
        'key' => 'untranslated',
        'text' => 'Untranslated',
        'translated_at' => null,
    ]);

    $job = new TranslateKeys($language);

    expect(fn () => $job->handle())->toThrow();
})->skip('Requires translation service');

it('has correct timeout and retry settings', function () {
    $job = new TranslateKeys;

    expect($job->timeout)->toBe(2200)
        ->and($job->tries())->toBe(5)
        ->and($job->backoff())->toBe(3);
});
