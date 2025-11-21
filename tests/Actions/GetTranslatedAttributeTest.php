<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\GetTranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;

it('returns translated attribute when translation exists', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->save();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Bonjour',
    ]);

    $result = GetTranslatedAttribute::run($model, 'title', 'fr');

    expect($result)->toBe('Bonjour');
});

it('returns original attribute when translation does not exist', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->save();

    $result = GetTranslatedAttribute::run($model, 'title', 'fr');

    expect($result)->toBe('Hello');
});

it('returns original attribute when locale matches app locale', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    app()->setLocale('en');

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->save();

    $result = GetTranslatedAttribute::run($model, 'title', 'en');

    expect($result)->toBe('Hello');
});

it('returns original attribute when attribute is not translatable', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->save();

    $result = GetTranslatedAttribute::run($model, 'id', 'fr');

    expect($result)->toBe($model->id);
});

