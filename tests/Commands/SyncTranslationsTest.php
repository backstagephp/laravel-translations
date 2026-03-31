<?php

use Backstage\Translations\Laravel\Commands\SyncTranslations;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

it('syncs translations for translatable models', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Config::set('translations.eloquent.translatable-models', [TestTranslatableModel::class]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    Artisan::call(SyncTranslations::class);

    expect(TranslatedAttribute::where('translatable_id', $model->id)->count())->toBeGreaterThan(0);
});

it('handles empty translatable models list', function () {
    Config::set('translations.eloquent.translatable-models', []);

    Artisan::call(SyncTranslations::class);

    expect(Artisan::output())->toContain('No translatable items found');
});

it('cleans orphaned translations', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    $orphan = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => 'NonExistent\\Model',
        'translatable_id' => 999,
        'attribute' => 'title',
        'translated_attribute' => 'Test',
    ]);

    $orphanId = $orphan->id;

    Artisan::call(SyncTranslations::class);

    $deleted = TranslatedAttribute::withTrashed()->find($orphanId);
    expect($deleted)->not->toBeNull()
        ->and($deleted->trashed())->toBeTrue();
});
