<?php

use Backstage\Translations\Laravel\Domain\Translatables\Actions\IsTranslatableAttribute;
use Backstage\Translations\Laravel\Tests\Models\TestTranslatableModel;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;

it('returns true for translatable attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    
    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    $result = IsTranslatableAttribute::run($model, 'title');

    expect($result)->toBeTrue();
});

it('returns false for non-translatable attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English']);
    
    $model = new TestTranslatableModel();
    $model->title = 'Hello';
    $model->description = 'World';
    $model->save();

    $result = IsTranslatableAttribute::run($model, 'id');

    expect($result)->toBeFalse();
});

