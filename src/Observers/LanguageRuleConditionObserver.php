<?php

namespace Backstage\Translations\Laravel\Observers;

use Backstage\Translations\Laravel\Models\LanguageRuleCondition;

class LanguageRuleConditionObserver
{
    public function saving(LanguageRuleCondition $condition)
    {
        $type = $condition->getAttribute('type');

        if ($type === 'must' || $type === 'must_not') {
            $condition->multiple_value = [];
        }
    }
}
