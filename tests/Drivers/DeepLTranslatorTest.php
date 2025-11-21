<?php

use Backstage\Translations\Laravel\Drivers\DeepLTranslator;
use Illuminate\Support\Facades\Config;

it('translates text using DeepL', function () {
    Config::set('services.deepl.auth_key', 'test-key');
    Config::set('translations.translators.drivers.deep-l.options', []);

    $translator = new DeepLTranslator;

    expect(fn () => $translator->translate('Hello', 'en'))
        ->toThrow();
})->skip('Requires actual DeepL API key');

it('normalizes language codes correctly', function () {
    $translator = new DeepLTranslator;

    $reflection = new \ReflectionClass(DeepLTranslator::class);
    $method = $reflection->getMethod('normalizeLanguageCode');
    $method->setAccessible(true);

    expect($method->invoke(null, 'en'))->toBe('EN-GB')
        ->and($method->invoke(null, 'pt'))->toBe('PT-PT')
        ->and($method->invoke(null, 'zh'))->toBe('ZH-HANS')
        ->and($method->invoke(null, 'es'))->toBe('ES')
        ->and($method->invoke(null, 'fr'))->toBe('FR');
});

it('returns text as-is when it is a ULID', function () {
    Config::set('services.deepl.auth_key', 'test-key');

    $translator = new DeepLTranslator;
    $ulid = '01ARZ3NDEKTSV4RRFFQ69G5FAV';

    $result = $translator->translate($ulid, 'fr');

    expect($result)->toBe($ulid);
});

it('throws exception when DeepL auth key is not set', function () {
    Config::set('services.deepl.auth_key', null);

    $translator = new DeepLTranslator;

    expect(fn () => $translator->translate('Hello', 'fr'))
        ->toThrow(\RuntimeException::class, 'DeepL auth key is not set');
});
