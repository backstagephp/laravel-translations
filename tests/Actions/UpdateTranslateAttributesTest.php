<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\UpdateTranslateAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;

it('updates translate attributes for all languages', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    UpdateTranslateAttributes::run($model, ['title', 'description']);

    $translations = TranslatedAttribute::where('translatable_id', $model->id)->get();

    expect($translations->count())->toBeGreaterThan(0);
})->skip('Requires translation service');

it('translates each attribute for each language with overwrite true', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    UpdateTranslateAttributes::run($model, ['title']);

    $titleTranslations = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('attribute', 'title')
        ->get();

    expect($titleTranslations->count())->toBe(2);
})->skip('Requires translation service');
