<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

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
        'type' => 'string',
        'value' => 'array',
    ];

    public function languageRule()
    {
        return $this->belongsTo(LanguageRule::class);
    }

    public function getTextualQuery(): string
    {
        $key = $this->key;

        if ($this->type !== 'must' && $this->type !== 'must_not') {
            return '';
        }

        $values = $this->value ?? [];

        if (empty($values) || ! is_array($values)) {
            report(new \Exception('Value is null or empty for key: '.$key.' and type: '.$this->type.' and id: '.$this->id));

            return '';
        }

        $resultingQuery = [];
        $resultingQuery[] = 'The following rules must be applied while translating the text:';

        if (count($values) === 1) {
            if ($this->type === 'must') {
                $resultingQuery[] = "{$key} must translate to '{$values[0]}'";
            }

            if ($this->type === 'must_not') {
                $resultingQuery[] = "{$key} must not translate to '{$values[0]}'";
            }
        } else {
            if ($this->type === 'must') {
                $resultingQuery[] = "{$key} must translate to one of these options: '".implode("', '", $values)."'";
            }

            if ($this->type === 'must_not') {
                $resultingQuery[] = "{$key} must not translate to any of these options: '".implode("', '", $values)."'";
            }
        }

        return implode("\n", $resultingQuery);
    }
}
