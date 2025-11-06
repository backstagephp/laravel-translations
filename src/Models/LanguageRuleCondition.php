<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Backstage\Translations\Laravel\Enums\LanguageRuleConditionType;
use Backstage\Translations\Laravel\Observers\LanguageRuleConditionObserver;

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
        'multiple_value'
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
}
