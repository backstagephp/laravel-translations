<?php

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Illuminate\Support\Facades\Event;

it('can get translatable attributes', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $model = new TestTranslatableModel;
    $model->title = 'Test Title';
    $model->description = 'Test Description';
    $model->save();

    expect($model->getTranslatableAttributes())->toBe(['title', 'description']);
});

it('can check if attribute is translatable', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $model = new TestTranslatableModel;
    $model->title = 'Test Title';
    $model->save();

    expect($model->isTranslatableAttribute('title'))->toBeTrue()
        ->and($model->isTranslatableAttribute('id'))->toBeFalse();
});

it('can get translated attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    Language::create(['code' => 'fr', 'name' => 'French']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Bonjour',
    ]);

    expect($model->getTranslatedAttribute('title', 'fr'))->toBe('Bonjour');
});

it('returns original attribute when translation does not exist', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    expect($model->getTranslatedAttribute('title', 'fr'))->toBe('Hello');
});

it('can get all translated attributes', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    Language::create(['code' => 'fr', 'name' => 'French']);

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

    $translated = $model->getTranslatedAttributes('fr');

    expect($translated)->toHaveKey('title')
        ->and($translated['title'])->toBe('Bonjour');
});

it('can push translated attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    Language::create(['code' => 'fr', 'name' => 'French']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    $model->pushTranslateAttribute('title', 'Bonjour', 'fr');

    $translated = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('attribute', 'title')
        ->where('code', 'fr')
        ->first();

    expect($translated)->not->toBeNull()
        ->and($translated->translated_attribute)->toBe('Bonjour');
});

it('has translatableAttributes relationship', function () {
    Language::create(['code' => 'fr', 'name' => 'French']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    TranslatedAttribute::where('translatable_id', $model->id)->delete();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Bonjour',
    ]);

    expect($model->translatableAttributes)->toHaveCount(1)
        ->and($model->translatableAttributes->first()->attribute)->toBe('title');
});

it('can get translatable attribute rules for attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    expect($model->getTranslatableAttributeRulesFor('title'))->toBe('*');
});

it('can sync translations on model creation', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    Event::fake();

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    expect($model->translatableAttributes)->toHaveCount(0);
});

it('can update translate attributes', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    Language::create(['code' => 'fr', 'name' => 'French']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    $model->updateTranslateAttributes(['title']);

    expect($model->translatableAttributes)->toHaveCount(2);
})->skip('Requires translation service');

it('can update attributes if translatable', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    Language::create(['code' => 'fr', 'name' => 'French']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    $model->updateAttributesIfTranslatable(['title']);

    expect($model->translatableAttributes)->toHaveCount(2);
})->skip('Requires translation service');

it('deletes translated attributes when model is deleted', function () {
    Language::create(['code' => 'fr', 'name' => 'French']);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    TranslatedAttribute::create([
        'code' => 'fr',
        'translatable_type' => get_class($model),
        'translatable_id' => $model->id,
        'attribute' => 'title',
        'translated_attribute' => 'Bonjour',
    ]);

    $model->delete();

    expect(TranslatedAttribute::where('translatable_id', $model->id)->count())->toBe(0);
});
