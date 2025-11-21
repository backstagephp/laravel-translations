<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttribute;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;

it('translates attribute to target language', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    $result = TranslateAttribute::run($model, 'title', 'fr');

    expect($result)->toBeString();

    $translated = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('code', 'fr')
        ->first();

    expect($translated)->not->toBeNull();
})->skip('Requires translation service');

it('does not overwrite existing translation when overwrite is false', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Existing',
    ]);

    $result = TranslateAttribute::run($model, 'title', 'fr', false);

    $translated = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('code', 'fr')
        ->where('attribute', 'title')
        ->first();

    expect($translated->translated_attribute)->toBe('Existing');
})->skip('Requires translation service');

it('overwrites existing translation when overwrite is true', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Existing',
    ]);

    $result = TranslateAttribute::run($model, 'title', 'fr', true);

    expect($result)->toBeString();
})->skip('Requires translation service');

it('handles array attributes', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = json_encode(['key1' => 'Hello', 'key2' => 'World']);
    $model->save();

    $result = TranslateAttribute::run($model, 'title', 'fr');

    expect($result)->toBeArray();
})->skip('Requires translation service');
