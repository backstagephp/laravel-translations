<?php

use Backstage\Translations\Laravel\Domain\Scanner\Actions\FindTranslatables;
use Illuminate\Support\Facades\Config;

it('scans for translation strings', function () {
    Config::set('translations.scan.paths', [__DIR__ . '/../../src']);
    Config::set('translations.scan.extensions', ['*.php']);
    Config::set('translations.scan.functions', ['trans', '__']);

    $result = FindTranslatables::scan();

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

it('extracts namespace from translation key', function () {
    $key = 'namespace::group.key';

    $reflection = new \ReflectionClass(FindTranslatables::class);
    $method = $reflection->getMethod('extractNamespace');
    $method->setAccessible(true);

    $result = $method->invoke(null, $key);

    expect($result)->toBe('namespace');
});

it('extracts group from translation key', function () {
    $key = 'group.key';

    $reflection = new \ReflectionClass(FindTranslatables::class);
    $method = $reflection->getMethod('extractGroup');
    $method->setAccessible(true);

    $result = $method->invoke(null, $key);

    expect($result)->toBe('group');
});

it('returns null namespace when key has no namespace', function () {
    $key = 'group.key';

    $reflection = new \ReflectionClass(FindTranslatables::class);
    $method = $reflection->getMethod('extractNamespace');
    $method->setAccessible(true);

    $result = $method->invoke(null, $key);

    expect($result)->toBeNull();
});

it('returns null group when key has no group', function () {
    $key = 'welcome';

    $reflection = new \ReflectionClass(FindTranslatables::class);
    $method = $reflection->getMethod('extractGroup');
    $method->setAccessible(true);

    $result = $method->invoke(null, $key);

    expect($result)->toBeNull();
});
