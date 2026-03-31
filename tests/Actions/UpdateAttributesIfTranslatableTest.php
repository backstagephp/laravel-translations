<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\UpdateAttributesIfTranslatable;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;

it('updates translatable attributes for all languages', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    UpdateAttributesIfTranslatable::run($model, ['title']);

    $translations = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('attribute', 'title')
        ->get();

    expect($translations->count())->toBeGreaterThan(0);
})->skip('Requires translation service');

it('calls translateAttributeForAllLanguages with overwrite true', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Old',
    ]);

    UpdateAttributesIfTranslatable::run($model, ['title']);

    $translation = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('code', 'fr')
        ->where('attribute', 'title')
        ->first();

    expect($translation)->not->toBeNull();
})->skip('Requires translation service');
