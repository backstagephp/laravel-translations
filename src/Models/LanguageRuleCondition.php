<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Observers\LanguageRuleConditionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

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
        'type' => 'string',
        'value' => 'string',
        'multiple_value' => 'array',
    ];

    public function languageRule()
    {
        return $this->belongsTo(LanguageRule::class);
    }

    public function getTextualQuery(): string
    {
        $key = $this->key;
        $value = null;
        if ($this->type === 'must' || $this->type === 'must_not') {
            $value = $this->value;
        }

        if ($this->type === 'must_multiple' || $this->type === 'must_not_multiple') {
            $value = $this->multiple_value;
        }

        if ($value === null) {
            report(new \Exception('Value is null for key: '.$key.' and type: '.$this->type.' and id: '.$this->id));

            return '';
        }

        $resultingQuery = [];

        if (is_array($value) && count($value) > 0 || ! is_array($value) && $value !== '' || $value !== null) {
            $resultingQuery[] = 'The following rules must be applied while translating the text:';
        }

        if ($this->type === 'must' || $this->type === 'must_not') {
            if ($this->type === 'must') {
                $resultingQuery[] = "{$key} must translate to '{$value}'";
            }

            if ($this->type === 'must_not') {
                $resultingQuery[] = "{$key} must not translate to '{$value}'";
            }
        }

        if ($this->type === 'must_multiple') {
            $values = $value;

            $resultingQuery[] = "{$key} must translate to one of these options: '".implode("', '", $values)."'";
        }

        if ($this->type === 'must_not_multiple') {
            $values = $value;

            $resultingQuery[] = "{$key} must not translate to any of these options: '".implode("', '", $values)."'";
        }

        return implode("\n", $resultingQuery);
    }
}
