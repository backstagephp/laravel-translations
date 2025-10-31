<?php

namespace Backstage\Translations\Laravel\Enums;

enum LanguageRuleConditionType: string
{
    case MUST = 'must';
    case MUST_NOT = 'must_not';
    case MUST_REPLACE = 'must_replace';
    case MUST_NOT_REPLACE = 'must_not_replace';

    public function mutateValue(string $value): string
    {
        return match ($this) {
            self::MUST, self::MUST_NOT => $value,
            self::MUST_NOT_REPLACE, self::MUST_REPLACE => json_decode($value, true),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}