<?php

use Backstage\Translations\Laravel\Events\LanguageAdded;
use Backstage\Translations\Laravel\Models\Language;

it('can create LanguageAdded event', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    $event = new LanguageAdded($language);

    expect($event->language)->toBe($language)
        ->and($event->language->code)->toBe('en');
});

