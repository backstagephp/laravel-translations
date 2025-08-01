<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Facades\Translator;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttribute
{
    use AsAction;

    public function handle(object $model, string $attribute, string $targetLanguage, bool $overwrite = false): mixed
    {
        /**
         * @var mixed $originalValue
         */
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

        /**
         * @var mixed $attributeValue
         */
        $attributeValue = $model->getAttribute($attribute);

        $attributeValue = json_decode($attributeValue, true) ?? $attributeValue;

        /**
         * @var mixed $translated
         */
        $translated = is_array($attributeValue) ? static::translateArray($model, $attributeValue, $attribute, $targetLanguage) : static::translate($attributeValue, $targetLanguage);

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
        if (is_string($value) || is_numeric($value)) {
            /**
             * @var mixed $translated
             */
            $translated = Translator::with(config('translations.translators.default'))->translate($value, $targetLanguage);

            return $translated;
        }

        return $value;
    }

    protected static function translateArray(object $model, array $data, string $attribute, string $targetLanguage): array
    {
        /**
         * @var array $rules
         */
        $rules = $model->getTranslatableAttributeRulesFor($attribute);

        if ($rules === '*') {
            return static::translateAllStringsInArray($data, $targetLanguage);
        }

        collect($rules)->each(function ($path) use (&$data, $targetLanguage) {
            $segments = explode('.', $path);

            if (count($segments) === 1) {
                $data = static::translateAllByKey($data, $segments[0], $targetLanguage);

                return;
            }

            /**
             * @var array $data
             */
            $data = static::translatePath($data, $segments, $targetLanguage);
        });

        return $data;
    }

    protected static function translateAllByKey(array $data, string $key, string $targetLanguage): array
    {
        return collect($data)->map(function ($value, $k) use ($key, $targetLanguage) {
            if (is_array($value)) {
                return static::translateAllByKey($value, $key, $targetLanguage);
            }

            if ($k === $key && (is_string($value) || is_numeric($value))) {
                return static::translate($value, $targetLanguage);
            }

            return $value;
        })->toArray();
    }

    protected static function translateKeyAtRoot(array $data, string $key, string $targetLanguage): array
    {
        if (! array_key_exists($key, $data)) {
            return $data;
        }

        /**
         * @var mixed $value
         */
        $value = $data[$key];

        if (! is_string($value) && ! is_numeric($value)) {
            return $data;
        }

        $data[$key] = static::translate($value, $targetLanguage);

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

        if (! array_key_exists($segment, $data)) {
            return $data;
        }

        if ($segments === []) {
            /**
             * @var mixed $value
             */
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

                continue;
            }

            if (! is_string($value) && ! is_numeric($value)) {
                continue;
            }

            $data[$key] = static::translate($value, $targetLanguage);
        }

        return $data;
    }
}
