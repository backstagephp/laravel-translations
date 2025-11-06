<?php

namespace Backstage\Translations\Laravel\Observers;

use Backstage\Translations\Laravel\Enums\LanguageRuleConditionType;
use Backstage\Translations\Laravel\Models\LanguageRuleCondition;

class LanguageRuleConditionObserver
{
    public function saving(LanguageRuleCondition $condition)
    {
        $type = $condition->getAttribute('type');
        $originalType = $condition->getOriginal('type');

        $singleTypes = [
            LanguageRuleConditionType::MUST,
            LanguageRuleConditionType::MUST_NOT,
        ];

        $multipleTypes = [
            LanguageRuleConditionType::MUST_MULTIPLE,
            LanguageRuleConditionType::MUST_NOT_MULTIPLE,
        ];

        if (in_array($type, $singleTypes, true)) {
            $condition->multiple_value = [];
        }

        // dd($condition->toArray());
    }
}
