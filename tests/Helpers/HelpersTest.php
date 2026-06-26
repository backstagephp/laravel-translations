<?php

it('localized_country_name returns country name', function () {
    $result = support()->localizedCountryName('en-US', 'en');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('localized_country_name handles code without country', function () {
    $result = support()->localizedCountryName('en', 'en');

    expect($result)->toBeString();
});

it('localized_language_name returns language name', function () {
    $result = support()->localizedLanguageName('en', 'en');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('localized_language_name handles locale code', function () {
    $result = support()->localizedLanguageName('en-US', 'en');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('localized_country_name uses app locale when not provided', function () {
    app()->setLocale('fr');

    $result = support()->localizedCountryName('en-US');

    expect($result)->toBeString();
});

it('localized_language_name uses app locale when not provided', function () {
    app()->setLocale('fr');

    $result = support()->localizedLanguageName('en');

    expect($result)->toBeString();
});
