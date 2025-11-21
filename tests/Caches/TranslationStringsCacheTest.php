<?php

use Backstage\Translations\Laravel\Caches\TranslationStringsCache;
use Backstage\Translations\Laravel\Models\Translation;
use Backstage\Translations\Laravel\Models\Language;

it('collects translations grouped by code, group, and namespace', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'namespace' => '*',
    ]);

    Translation::create([
        'code' => 'fr',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Bienvenue',
        'namespace' => '*',
    ]);

    $result = TranslationStringsCache::collect();

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('en')
        ->and($result)->toHaveKey('fr')
        ->and($result['en'])->toHaveKey('test')
        ->and($result['en']['test'])->toHaveKey('*')
        ->and($result['en']['test']['*'])->toHaveKey('welcome')
        ->and($result['en']['test']['*']['welcome'])->toBe('Welcome');
});

it('handles translations without group', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => null,
        'key' => 'welcome',
        'text' => 'Welcome',
        'namespace' => '*',
    ]);

    $result = TranslationStringsCache::collect();

    expect($result)->toBeArray()
        ->and($result['en'])->toHaveKey('*');
});

it('runs cache update', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'namespace' => '*',
    ]);

    $cache = new TranslationStringsCache();
    $result = $cache->run();

    expect($result)->toBeArray();
});

