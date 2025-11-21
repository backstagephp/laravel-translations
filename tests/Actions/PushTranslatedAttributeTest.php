<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\PushTranslatedAttribute;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;

it('creates translated attribute when language exists', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    PushTranslatedAttribute::run($model, 'title', 'Bonjour', 'fr');

    $translated = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('attribute', 'title')
        ->where('code', 'fr')
        ->first();

    expect($translated)->not->toBeNull()
        ->and($translated->translated_attribute)->toBe('Bonjour')
        ->and($translated->translated_at)->not->toBeNull();
});

it('creates language when it does not exist', function () {
    Language::create(['code' => 'en', 'name' => 'English', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    PushTranslatedAttribute::run($model, 'title', 'Bonjour', 'fr');

    expect(Language::where('code', 'fr')->exists())->toBeTrue();

    $translated = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('code', 'fr')
        ->first();

    expect($translated)->not->toBeNull();
});

it('updates existing translated attribute', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    $model = new TestTranslatableModel;
    $model->title = 'Hello';
    $model->save();

    PushTranslatedAttribute::run($model, 'title', 'Bonjour', 'fr');
    PushTranslatedAttribute::run($model, 'title', 'Salut', 'fr');

    $translated = TranslatedAttribute::where('translatable_id', $model->id)
        ->where('code', 'fr')
        ->first();

    expect($translated->translated_attribute)->toBe('Salut');
});
