<?php

use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Models\Language;

it('can create LanguageDeleted event', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    $event = new LanguageDeleted($language);

    expect($event->language)->toBe($language)
        ->and($event->language->code)->toBe('en');
});
