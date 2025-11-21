<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\SyncTranslations;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;

it('syncs translations for all translatable attributes', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    SyncTranslations::run($model);

    $translations = TranslatedAttribute::where('translatable_id', $model->id)->get();

    expect($translations->count())->toBeGreaterThan(0);
})->skip('Requires translation service');

it('calls translateAttributeForAllLanguages for each attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    SyncTranslations::run($model);

    $titleTranslations = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('attribute', 'title')
        ->get();

    $descriptionTranslations = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('attribute', 'description')
        ->get();

    expect($titleTranslations->count())->toBeGreaterThan(0)
        ->and($descriptionTranslations->count())->toBeGreaterThan(0);
})->skip('Requires translation service');

