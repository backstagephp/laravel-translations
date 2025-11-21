<?php

use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Listners\HandleLanguageCodeChanges;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;

it('updates translation codes when language code changes', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
        'translated_at' => now(),
    ]);

    $language->code = 'en-US';
    $language->save();

    $event = new LanguageCodeChanged($language);
    $listener = new HandleLanguageCodeChanges();
    $listener->handle($event);

    $translation->refresh();

    expect($translation->code)->toBe('en-US')
        ->and($translation->translated_at)->toBeNull();
});

