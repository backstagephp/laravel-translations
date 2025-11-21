<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributeForAllLanguages;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;

it('translates attribute for all languages', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);
    Language::create(['code' => 'de', 'name' => 'German', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    $result = TranslateAttributeForAllLanguages::run($model, 'title');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('en')
        ->and($result)->toHaveKey('fr')
        ->and($result)->toHaveKey('de');
})->skip('Requires translation service');

it('throws exception when no languages exist', function () {
    TranslatedAttribute::query()->delete();
    Language::query()->delete();

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->id = 999;
    $model->exists = true;

    expect(fn () => TranslateAttributeForAllLanguages::run($model, 'title'))
        ->toThrow(\RuntimeException::class, 'No languages available');
});

it('respects overwrite flag', function () {
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

    $result = TranslateAttributeForAllLanguages::run($model, 'title', false);

    expect($result['fr'])->toBe('Existing');
})->skip('Requires translation service');
