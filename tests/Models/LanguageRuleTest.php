<?php

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\LanguageRule;
use Backstage\Translations\Laravel\Models\LanguageRuleCondition;

it('can create a language rule', function () {
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
        'global_instructions' => 'Test instructions',
    ]);

    expect($rule->code)->toBe('en')
        ->and($rule->name)->toBe('Test Rule')
        ->and($rule->global_instructions)->toBe('Test instructions');
});

it('has conditions relationship', function () {
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
    ]);

    $condition = LanguageRuleCondition::create([
        'language_rule_id' => $rule->id,
        'key' => 'test_key',
        'type' => 'must',
        'value' => ['value1', 'value2'],
    ]);

    expect($rule->conditions)->toHaveCount(1)
        ->and($rule->conditions->first()->id)->toBe($condition->id);
});

it('has language relationship', function () {
    $language = Language::create(['code' => 'en', 'name' => 'English']);
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
    ]);

    expect($rule->language)->not->toBeNull()
        ->and($rule->language->code)->toBe('en');
});

it('returns textual query with global instructions', function () {
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
        'global_instructions' => 'Test global instructions',
    ]);

    $query = $rule->getTextualQuery();

    expect($query)->toBeString()
        ->and($query)->toContain('global-instructions')
        ->and($query)->toContain('Test global instructions')
        ->and($query)->toContain('translation-rules-query-conditions');
});

it('returns textual query with conditions', function () {
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
    ]);

    $condition = LanguageRuleCondition::create([
        'language_rule_id' => $rule->id,
        'key' => 'test_key',
        'type' => 'must',
        'value' => ['value1'],
    ]);

    $query = $rule->getTextualQuery();

    expect($query)->toBeString()
        ->and($query)->toContain('translation-rules-query-condition-subquery')
        ->and($query)->toContain('test_key');
});

it('supports soft deletes', function () {
    $rule = LanguageRule::create([
        'code' => 'en',
        'name' => 'Test Rule',
    ]);

    $rule->delete();

    expect($rule->trashed())->toBeTrue()
        ->and(LanguageRule::find($rule->id))->toBeNull()
        ->and(LanguageRule::withTrashed()->find($rule->id))->not->toBeNull();
});

