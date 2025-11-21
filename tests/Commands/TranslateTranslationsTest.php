<?php

use Backstage\Translations\Laravel\Commands\TranslateTranslations;
use Backstage\Translations\Laravel\Jobs\TranslateKeys;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;

it('translates for all languages when no code option provided', function () {
    Queue::fake();

    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Artisan::call(TranslateTranslations::class);

    Queue::assertPushed(TranslateKeys::class);
});

it('translates for specific language when code option provided', function () {
    Queue::fake();

    $language = Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Artisan::call(TranslateTranslations::class, ['--code' => 'fr']);

    Queue::assertPushed(TranslateKeys::class, function ($job) {
        return $job->lang && $job->lang->code === 'fr';
    });
});

it('fails when language code does not exist', function () {
    Artisan::call(TranslateTranslations::class, ['--code' => 'xx']);

    expect(Artisan::output())->toContain('not found');
});

it('updates existing translations when update flag is provided', function () {
    Queue::fake();

    $language = Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => now(),
    ]);

    Artisan::call(TranslateTranslations::class, ['--update' => true]);

    $translation->refresh();

    expect($translation->translated_at)->toBeNull();
});

it('updates translations for specific language with update flag', function () {
    Queue::fake();

    $language = Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => now(),
    ]);

    Artisan::call(TranslateTranslations::class, ['--code' => 'en', '--update' => true]);

    $translation->refresh();

    expect($translation->translated_at)->toBeNull();
});

it('fails when updating translations for non-existent language', function () {
    Artisan::call(TranslateTranslations::class, ['--code' => 'xx', '--update' => true]);

    expect(Artisan::output())->toContain('No translations found');
});
