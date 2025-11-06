<?php

namespace Backstage\Translations\Laravel\Enums;

enum LanguageRuleConditionType: string
{
    case MUST = 'must';
    case MUST_NOT = 'must_not';
    case MUST_MULTIPLE = 'MUST_MULTIPLE';
    case MUST_NOT_MULTIPLE = 'MUST_NOT_MULTIPLE';

    public function mutateValue(string $value): string
    {
        return match ($this) {
            self::MUST, self::MUST_NOT => $value,
            self::MUST_NOT_MULTIPLE, self::MUST_MULTIPLE => json_decode($value, true),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
