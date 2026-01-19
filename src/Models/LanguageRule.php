<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageRule extends Model
{
    use SoftDeletes;

    protected $table = 'language_rules';

    protected $fillable = [
        'code',
        'name',
        'global_instructions',
    ];

    public function conditions()
    {
        return $this->hasMany(LanguageRuleCondition::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'code', 'code');
    }

    public function getTextualQuery(): string
    {
        $text = '';

        if ($this->global_instructions) {
            $text .= "\n<global-instructions>".str($this->global_instructions)->stripTags()->toString().'</global-instructions>';
        }

        $this->load('conditions')->conditions->each(function (LanguageRuleCondition $condition) use (&$text) {
            if ($query = $condition->getTextualQuery()) {
                $text .= "\n<translation-rules-query-condition-subquery>".$query.'</translation-rules-query-condition-subquery>';
            }
        });

        return '<translation-rules-query-conditions>'.trim($text).'</translation-rules-query-conditions>';
    }
}
