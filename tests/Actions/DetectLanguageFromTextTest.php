<?php

use Backstage\Translations\Laravel\Domain\Detection\Actions\DetectLanguageFromText;

it('detects language from text', function () {
    $text = 'Bonjour le monde';

    $result = DetectLanguageFromText::run($text);

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('throws exception when language detection fails', function () {
    $text = '';

    expect(fn () => DetectLanguageFromText::run($text))
        ->toThrow(\Exception::class);
})->skip('Google Translate may detect language even for empty text');
