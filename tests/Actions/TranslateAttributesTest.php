<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributes;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Backstage\Translations\Laravel\Models\Language;

it('translates all translatable attributes to target language', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    $result = TranslateAttributes::run($model, 'fr');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('title')
        ->and($result)->toHaveKey('description');
})->skip('Requires translation service');

it('uses app locale when target language is null', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    app()->setLocale('en');

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    $result = TranslateAttributes::run($model, null);

    expect($result)->toBeArray();
})->skip('Requires translation service');

