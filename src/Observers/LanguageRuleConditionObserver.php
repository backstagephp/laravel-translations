<?php

namespace Backstage\Translations\Laravel\Observers;

use Backstage\Translations\Laravel\Enums\LanguageRuleConditionType;
use Backstage\Translations\Laravel\Models\LanguageRuleCondition;

class LanguageRuleConditionObserver
{
    public function saving(LanguageRuleCondition $languageRuleCondition)
    {
        if ($languageRuleCondition->type === LanguageRuleConditionType::MUST || $languageRuleCondition->type === LanguageRuleConditionType::MUST_NOT) {
            $languageRuleCondition->multiple_value = [];
        }
    }
}
