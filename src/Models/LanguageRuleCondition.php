<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Enums\LanguageRuleConditionType;
use Backstage\Translations\Laravel\Observers\LanguageRuleConditionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

/**
 * @property LanguageRuleConditionType $type
 */
#[ObservedBy(LanguageRuleConditionObserver::class)]
class LanguageRuleCondition extends Model
{
    protected $table = 'language_rules_conditions';

    protected $fillable = [
        'language_rule_id',
        'key',
        'type',
        'value',
        'multiple_value',
    ];

    protected $casts = [
        'type' => LanguageRuleConditionType::class,
        'value' => 'string',
        'multiple_value' => 'array',
    ];

    public function languageRule()
    {
        return $this->belongsTo(LanguageRule::class);
    }

    public function getTextualQuery(): string
    {
        return $this->type->buildTextualQuery($this->key, $this->value ?? $this->multiple_value);
    }
}
