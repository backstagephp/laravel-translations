<?php

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Models\LanguageRule;

it('can create a language', function () {
    $language = Language::create([
        'code' => 'en-US',
        'name' => 'English',
        'native' => 'English',
        'active' => true,
    ]);

    expect($language->code)->toBe('en-US')
        ->and($language->name)->toBe('English')
        ->and($language->native)->toBe('English')
        ->and($language->active)->toBeTrue();
});

it('has active scope', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => false]);

    $activeLanguages = Language::active()->get();

    expect($activeLanguages)->toHaveCount(1)
        ->and($activeLanguages->first()->code)->toBe('en');
});

it('can get default language', function () {
    Language::query()->delete();
    $en = Language::create(['code' => 'en', 'name' => 'English', 'default' => false]);
    $fr = Language::create(['code' => 'fr', 'name' => 'French', 'default' => true]);

    $default = Language::default();

    expect($default)->not->toBeNull()
        ->and($default->code)->toBe('fr')
        ->and($default->default)->toBeTrue();
});

it('returns null when no default language exists', function () {
    Language::query()->delete();
    $en = Language::create(['code' => 'en', 'name' => 'English']);
    $fr = Language::create(['code' => 'fr', 'name' => 'French']);
    
    Language::whereIn('code', ['en', 'fr'])->update(['default' => false]);

    expect(Language::default())->toBeNull();
});

it('has translatableAttributes relationship', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $translatedAttribute = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => 'App\\Models\\Test',
        'translatable_id' => 1,
        'attribute' => 'title',
        'translated_attribute' => 'Test Title',
    ]);

    expect($language->translatableAttributes()->count())->toBe(1);
});

it('has translations relationship', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Welcome',
    ]);

    expect($language->translations)->toHaveCount(1)
        ->and($language->translations->first()->id)->toBe($translation->id);
});

it('has languageRules relationship', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
    ]);

    expect($language->languageRules)->toHaveCount(1)
        ->and($language->languageRules->first()->id)->toBe($rule->id);
});

it('can get language code attribute', function () {
    $language = Language::create(['code' => 'en-US', 'name' => 'English']);

    expect($language->languageCode)->toBe('en');
});

it('can get country code attribute', function () {
    $language = Language::create(['code' => 'en-US', 'name' => 'English']);

    expect($language->countryCode)->toBe('US');
});

it('can get localized country name', function () {
    $language = Language::create(['code' => 'en-US', 'name' => 'English']);

    expect($language->localizedCountryName)->toBeString()
        ->and($language->localizedCountryName)->not->toBeEmpty();
});

it('can get localized language name', function () {
    $language = Language::create(['code' => 'en-US', 'name' => 'English']);

    expect($language->localizedLanguageName)->toBeString()
        ->and($language->localizedLanguageName)->not->toBeEmpty();
});

it('returns empty string for textual rules query when no rules exist', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);

    expect($language->getTextualRulesQuery())->toBe('');
});

it('returns textual rules query when rules exist', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
        'global_instructions' => 'Test instructions',
    ]);

    $query = $language->getTextualRulesQuery();

    expect($query)->toBeString()
        ->and($query)->toContain('translation-rules-query-base-rules')
        ->and($query)->toContain('Test instructions');
});

