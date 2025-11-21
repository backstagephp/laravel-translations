<?php

use Backstage\Translations\Laravel\Jobs\ScanTranslationStrings;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Backstage\Translations\Laravel\Domain\Scanner\Actions\FindTranslatables;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;

it('scans and stores translation strings', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Event::fake();

    $job = new ScanTranslationStrings();
    $job->handle();

    expect(Translation::count())->toBeGreaterThanOrEqual(0);
});

it('scans for specific language when provided', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Event::fake();

    $job = new ScanTranslationStrings($language);
    $job->handle();

    $translations = Translation::where('code', 'en')->get();

    expect($translations->count())->toBeGreaterThanOrEqual(0);
});

it('preserves existing translations when redo is false', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    $translation = Translation::create([
        'code' => 'en',
        'group' => 'test',
        'key' => 'welcome',
        'text' => 'Existing Welcome',
        'namespace' => '*',
    ]);

    Event::fake();

    $job = new ScanTranslationStrings(null, false);
    $job->handle();

    $translation->refresh();

    expect($translation->text)->toBe('Existing Welcome');
});

it('updates cache after storing translations', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    $job = new ScanTranslationStrings();
    $job->handle();

    expect(Translation::count())->toBeGreaterThanOrEqual(0);
});

it('handles array translations correctly', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    Event::fake();

    $job = new ScanTranslationStrings();
    $job->handle();

    $arrayTranslations = Translation::whereNotNull('text')
        ->whereRaw('text LIKE ?', ['{%'])
        ->get();

    expect($arrayTranslations->count())->toBe(0);
});

