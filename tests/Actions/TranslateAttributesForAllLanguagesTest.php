<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributesForAllLanguages;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Backstage\Translations\Laravel\Models\Language;

it('translates all attributes for all languages', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    $result = TranslateAttributesForAllLanguages::run($model);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('en')
        ->and($result)->toHaveKey('fr')
        ->and($result['en'])->toHaveKey('title')
        ->and($result['en'])->toHaveKey('description');
})
->skip('Requires translation service');

