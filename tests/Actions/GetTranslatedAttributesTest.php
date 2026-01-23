<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\GetTranslatedAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;

it('returns all translated attributes for locale', function () {
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
        'translated_attribute' => 'Bonjour',
    ]);

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'description',
        'translated_attribute' => 'Monde',
    ]);

    $result = GetTranslatedAttributes::run($model, 'fr');

    expect($result)->toHaveKey('title')
        ->and($result)->toHaveKey('description')
        ->and($result['title'])->toBe('Bonjour')
        ->and($result['description'])->toBe('Monde');
});

it('returns original attributes when no translations exist', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    $result = GetTranslatedAttributes::run($model, 'fr');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('title')
        ->and($result)->toHaveKey('description');
});
