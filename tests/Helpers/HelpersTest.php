<?php

it('localized_country_name returns country name', function () {
    $result = localized_country_name('en-US', 'en');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('localized_country_name handles code without country', function () {
    $result = localized_country_name('en', 'en');

    expect($result)->toBeString();
});

it('localized_language_name returns language name', function () {
    $result = localized_language_name('en', 'en');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('localized_language_name handles locale code', function () {
    $result = localized_language_name('en-US', 'en');

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('localized_country_name uses app locale when not provided', function () {
    app()->setLocale('fr');

    $result = localized_country_name('en-US');

    expect($result)->toBeString();
});

it('localized_language_name uses app locale when not provided', function () {
    app()->setLocale('fr');

    $result = localized_language_name('en');

    expect($result)->toBeString();
});
