<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Backstage\Translations\Laravel\Enums\LanguageRuleConditionType;

/**
 * @property LanguageRuleConditionType $type
 */
class LanguageRuleCondition extends Model
{
    protected $table = 'language_rules_conditions';

    protected $fillable = [
        'language_rule_id',
        'key',
        'type',
        'value',
    ];

    protected $casts = [
        'type' => LanguageRuleConditionType::class,
    ];

    public function getValueAttribute()
    {
        return $this->type->mutateValue($this->value);
    }

    public function languageRule()
    {
        return $this->belongsTo(LanguageRule::class);
    }
}   