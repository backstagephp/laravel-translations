<?php

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Database\Eloquent\Model;

it('can create a translated attribute', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $attribute = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => 'App\\Models\\Test',
        'translatable_id' => 1,
        'attribute' => 'title',
        'translated_attribute' => 'Test Title',
    ]);

    expect($attribute->code)->toBe('en')
        ->and($attribute->translatable_type)->toBe('App\\Models\\Test')
        ->and($attribute->translatable_id)->toBe(1)
        ->and($attribute->attribute)->toBe('title')
        ->and($attribute->translated_attribute)->toBe('Test Title');
});

it('has translatable morph relationship', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $testModel = new class extends Model
    {
        protected $table = 'test_models';
    };
    $testModel->save();

    $attribute = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => get_class($testModel),
        'translatable_id' => $testModel->id,
        'attribute' => 'title',
        'translated_attribute' => 'Test Title',
    ]);

    expect($attribute->translatable)->not->toBeNull();
});

it('has language relationship', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $attribute = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => 'App\\Models\\Test',
        'translatable_id' => 1,
        'attribute' => 'title',
        'translated_attribute' => 'Test Title',
    ]);

    expect($attribute->language)->not->toBeNull()
        ->and($attribute->language->code)->toBe('en');
});

it('supports soft deletes', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $attribute = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => 'App\\Models\\Test',
        'translatable_id' => 1,
        'attribute' => 'title',
        'translated_attribute' => 'Test Title',
    ]);

    $attribute->delete();

    expect($attribute->trashed())->toBeTrue()
        ->and(TranslatedAttribute::find($attribute->id))->toBeNull()
        ->and(TranslatedAttribute::withTrashed()->find($attribute->id))->not->toBeNull();
});

it('casts translated_at to datetime', function () {
    Language::create(['code' => 'en', 'name' => 'English']);

    $attribute = TranslatedAttribute::create([
        'code' => 'en',
        'translatable_type' => 'App\\Models\\Test',
        'translatable_id' => 1,
        'attribute' => 'title',
        'translated_attribute' => 'Test Title',
        'translated_at' => now(),
    ]);

    expect($attribute->translated_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
