<?php

use Backstage\Translations\Laravel\Events\LanguageAdded;
use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Support\Facades\Event;

it('sets default to true when creating first language', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    expect($language->default)->toBeTrue()
        ->and($language->active)->toBeTrue();
});

it('sets active to true when creating first language', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    expect($language->active)->toBeTrue();
});

it('fires LanguageAdded event when language is created', function () {
    Event::fake([LanguageAdded::class]);

    Language::create(['code' => 'en', 'name' => 'English']);

    Event::assertDispatched(LanguageAdded::class);
});

it('ensures only one default language exists', function () {
    Language::query()->delete();
    $language1 = Language::create(['code' => 'en', 'name' => 'English', 'default' => true]);
    $language2 = Language::create(['code' => 'fr', 'name' => 'French', 'default' => true]);

    $language1->refresh();
    $language2->refresh();

    expect(Language::where('default', true)->count())->toBe(1)
        ->and($language2->default)->toBeTrue()
        ->and($language1->default)->toBeFalse();
});

it('prevents deactivating last active language', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    $language->active = false;
    $language->save();

    expect($language->fresh()->active)->toBeTrue();
});

it('fires LanguageCodeChanged event when code changes', function () {
    Event::fake([LanguageCodeChanged::class]);

    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $language->code = 'en-US';
    $language->save();

    Event::assertDispatched(LanguageCodeChanged::class);
});

it('reassigns default when default language is deleted', function () {
    $language1 = Language::create(['code' => 'en', 'name' => 'English', 'default' => true]);
    $language2 = Language::create(['code' => 'fr', 'name' => 'French', 'default' => false]);

    $language1->delete();

    expect($language2->fresh()->default)->toBeTrue();
});

it('fires LanguageDeleted event when language is deleted', function () {
    Event::fake([LanguageDeleted::class]);

    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $language->delete();

    Event::assertDispatched(LanguageDeleted::class);
});
