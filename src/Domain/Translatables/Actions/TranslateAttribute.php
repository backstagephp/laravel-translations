<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Facades\Translator;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttribute
{
    use AsAction;

    public function handle(object $model, string $attribute, string $targetLanguage, bool $overwrite = false): mixed
    {
        $originalValue = $model->getAttribute($attribute);

        if (
            ! $overwrite && $model->translatableAttributes()
            ->getQuery()
            ->where('attribute', $attribute)
            ->where('code', $targetLanguage)
            ->exists()
        ) {
            return $model->getTranslatedAttribute($attribute, $targetLanguage);
        }

        $attributeValue = $model->getAttribute($attribute);

        $translated = is_array($attributeValue)
            ? static::translateArray($model, $attributeValue, $attribute, $targetLanguage)
            : static::translate($attributeValue, $targetLanguage);

        if ($translated === null) {
            $translated = $model->translatableAttributes()
                ->where('attribute', $attribute)
                ->where('code', $targetLanguage)
                ->value('translated_attribute');
        }

        $model->pushTranslateAttribute($attribute, $translated, $targetLanguage);

        return $translated ?: $originalValue;
    }

    protected static function translate(mixed $value, string $targetLanguage): mixed
    {
        return is_string($value) || is_numeric($value)
            ? Translator::with(config('translations.translators.default'))->translate($value, $targetLanguage)
            : $value;
    }

    protected static function translateArray(object $model, array $data, string $attribute, string $targetLanguage): array
    {
        $rules = $model->getTranslatableAttributeRulesFor($attribute);

        if ($rules === '*') {
            return static::translateAllStringsInArray($data, $targetLanguage);
        }

        foreach ($rules as $path) {
            $segments = explode('.', $path);
            if (count($segments) === 1) {
                $data = static::translateAllByKey($data, $segments[0], $targetLanguage);
            } else {
                $data = static::translatePath($data, $segments, $targetLanguage);
            }
        }

        return $data;
    }

    protected static function translateAllByKey(array $data, string $key, string $targetLanguage): array
    {
        foreach ($data as $k => $value) {
            if (is_array($value)) {
                $data[$k] = static::translateAllByKey($value, $key, $targetLanguage);
            } elseif ($k === $key && (is_string($value) || is_numeric($value))) {
                $data = static::translateKeyAtRoot($data, $key, $targetLanguage);
                break;
            }
        }
        return $data;
    }

    protected static function translateKeyAtRoot(array $data, string $key, string $targetLanguage): array
    {
        if (array_key_exists($key, $data)) {
            $value = $data[$key];
            if (is_string($value) || is_numeric($value)) {
                $data[$key] = static::translate($value, $targetLanguage);
            }
        }
        return $data;
    }


    protected static function translatePath(array $data, array $segments, string $targetLanguage): array
    {
        if ($segments === []) {
            return $data;
        }

        $segment = array_shift($segments);

        if ($segment === '*') {
            foreach ($data as $key => $item) {
                if (is_array($item)) {
                    $data[$key] = static::translatePath($item, $segments, $targetLanguage);
                }
            }

            return $data;
        }

        if (!array_key_exists($segment, $data)) {
            return $data;
        }

        if ($segments === []) {
            $value = $data[$segment];
            if (is_string($value) || is_numeric($value)) {
                $data[$segment] = static::translate($value, $targetLanguage);
            }
            return $data;
        }

        if (is_array($data[$segment])) {
            $data[$segment] = static::translatePath($data[$segment], $segments, $targetLanguage);
        }

        return $data;
    }

    protected static function translateAllStringsInArray(array $data, string $targetLanguage): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = static::translateAllStringsInArray($value, $targetLanguage);
            } elseif (is_string($value) || is_numeric($value)) {
                $data[$key] = static::translate($value, $targetLanguage);
            }
        }

        return $data;
    }
}
