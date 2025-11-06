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

    public function buildTextualQuery(string $key, string|array $value)
    {
        $resultingQuery = [];

        if (is_array($value) && count($value) > 0 || ! is_array($value) && $value !== '' || $value !== null) {
            $resultingQuery[] = 'The following rules must be applied while translating the text:';
        }

        if ($this === self::MUST || $this === self::MUST_NOT) {
            if ($this === self::MUST) {
                $resultingQuery[] = "{$key} must translate to '{$value}'";
            }

            if ($this === self::MUST_NOT) {
                $resultingQuery[] = "{$key} must not translate to '{$value}'";
            }
        }

        if ($this === self::MUST_MULTIPLE) {
            $values = $value;

            $resultingQuery[] = "{$key} must translate to one of these options: '".implode("', '", $values)."'";
        }

        if ($this === self::MUST_NOT_MULTIPLE) {
            $values = $value;
            $resultingQuery[] = "{$key} must not translate to any of these options: '".implode("', '", $values)."'";
        }

        return implode("\n", $resultingQuery);
    }

    public function getLabel(): string {
        return match ($this) {
            self::MUST => __('Must'),
            self::MUST_NOT => __('Must Not'),
            self::MUST_MULTIPLE => __('Must Multiple'),
            self::MUST_NOT_MULTIPLE => __('Must Not Multiple'),
        };
    }
}
