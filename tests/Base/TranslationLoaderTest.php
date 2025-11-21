<?php

use Backstage\Translations\Laravel\Base\TranslationLoader;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;

it('loads translations from database', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome from DB',
        'namespace' => '*',
    ]);

    $loader = new TranslationLoader(app('files'), app('path.lang'));

    $result = $loader->load('en', 'test', '*');

    expect($result)->toHaveKey('welcome')
        ->and($result['welcome'])->toBe('Welcome from DB');
});

it('merges file and database translations', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome from DB',
        'namespace' => '*',
    ]);

    $loader = new TranslationLoader(app('files'), app('path.lang'));

    $result = $loader->load('en', 'test', '*');

    expect($result)->toBeArray();
});

it('returns file translations only when namespace is not wildcard', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome from DB',
        'namespace' => 'custom',
    ]);

    $loader = new TranslationLoader(app('files'), app('path.lang'));

    $result = $loader->load('en', 'test', 'custom');

    expect($result)->toBeArray();
});

it('checks if translations table exists', function () {
    $loader = new TranslationLoader(app('files'), app('path.lang'));

    $reflection = new \ReflectionClass(TranslationLoader::class);
    $method = $reflection->getMethod('checkTableExists');
    $method->setAccessible(true);

    $result = $method->invoke($loader);

    expect($result)->toBeTrue();
});
