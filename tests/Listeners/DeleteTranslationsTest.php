<?php

use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Listners\DeleteTranslations;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;

it('deletes translations when language is deleted', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
    ]);

    Translation::create([
        'code' => 'fr',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Bienvenue',
    ]);

    $event = new LanguageDeleted($language);
    $listener = new DeleteTranslations();
    $listener->handle($event);

    expect(Translation::where('code', 'en')->count())->toBe(0)
        ->and(Translation::where('code', 'fr')->count())->toBe(1);
});

