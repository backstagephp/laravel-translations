<?php

use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Models\Language;

it('can create LanguageCodeChanged event', function () {
    Language::query()->delete();
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    $event = new LanguageCodeChanged($language);

    expect($event->language)->toBe($language)
        ->and($event->language->code)->toBe('en');
});
