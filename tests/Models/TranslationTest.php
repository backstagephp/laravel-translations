<?php

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Event;

it('can create a translation', function () {
    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'namespace' => '*',
    ]);

    expect($translation->code)->toBe('en')
        ->and($translation->group)->toBe('test')
        ->and($translation->key)->toBe('welcome')
        ->and($translation->text)->toBe('Welcome')
        ->and($translation->namespace)->toBe('*');
});

it('has language relationship', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
    ]);

    expect($translation->language)->not->toBeNull()
        ->and($translation->language->code)->toBe('en');
});

it('can get language code attribute', function () {
    $translation = Translation::create([
        'code' => 'en-US',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
    ]);

    expect($translation->languageCode)->toBe('en');
});

it('can get country code attribute', function () {
    $translation = Translation::create([
        'code' => 'en-US',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
    ]);

    expect($translation->countryCode)->toBe('US');
});

it('updates cache when translation is saved', function () {
    Event::fake();

    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
    ]);

    $translation->update(['text' => 'Welcome Updated']);

    expect($translation->text)->toBe('Welcome Updated');
});

it('casts translated_at to datetime', function () {
    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => now(),
    ]);

    expect($translation->translated_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
