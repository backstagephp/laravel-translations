<?php

use Backstage\Translations\Laravel\Drivers\GoogleTranslator;

it('translates text using Google Translate', function () {
    $translator = new GoogleTranslator;

    $result = $translator->translate('Hello', 'fr');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
})->skip('Requires actual Google Translate API');

it('retries translation on failure', function () {
    $translator = new GoogleTranslator;

    $result = $translator->translate('Hello', 'fr');

    expect($result)->toBeString();
})->skip('Requires actual Google Translate API');
