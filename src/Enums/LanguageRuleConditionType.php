<?php

namespace Backstage\Translations\Laravel\Enums;

enum LanguageRuleConditionType: string
{
    case MUST = 'must';
    case MUST_NOT = 'must_not';
    case MUST_MULTIPLE = 'must_multiple';
    case MUST_NOT_MULTIPLE = 'must_not_multiple';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
