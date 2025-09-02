<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Facades\Translator;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttribute
{
    use AsAction;

    public function handle(TranslatesAttributes $model, string $attribute, string $targetLanguage, bool $overwrite = false, ?string $extraPrompt = null): mixed
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
        $translated = is_array($attributeValue) ? static::translateArray($model, $attributeValue, $attribute, $targetLanguage, $extraPrompt) : static::translate($attributeValue, $targetLanguage, $extraPrompt);

        if ($translated === null) {
            $translated = $model->translatableAttributes()
                ->where('attribute', $attribute)
                ->where('code', $targetLanguage)
                ->value('translated_attribute');
        }

        $model->pushTranslateAttribute($attribute, $translated, $targetLanguage);

        return $translated ?: $originalValue;
    }

    protected static function translate(mixed $value, string $targetLanguage, ?string $extraPrompt = null): mixed
    {
        if (is_string($value) || is_numeric($value)) {
            /**
             * @var mixed $translated
             */
            try {
                $translated = Translator::with(config('translations.translators.default'))->translate($value, $targetLanguage, $extraPrompt);
            } catch (\Throwable $e) {
                $avaiableDrivers = collect(config('translations.translators.drivers', []))
                    ->filter(function ($x, $driver) {
                        $textQuery = 'Test query';

                        try {
                            $translation = Translator::with($driver)->translate($textQuery, 'ru');

                            if ($translation === $textQuery) {
                                return false;
                            }

                            return true;
                        } catch (\Throwable $e) {
                            return false;
                        }
                    })
                    ->keys();

                if ($avaiableDrivers->isEmpty()) {
                    info('No available translation drivers found.');

                    return $value;
                }

                info('Translation failed, using default driver.');
                $translated = Translator::with($avaiableDrivers->first())->translate($value, $targetLanguage, $extraPrompt);
            }

            return $translated;
        }

        return $value;
    }

    /**
     * Translate an array of attributes.
     *
     * @param  string|array  $rules
     */
    public static function translateArray(?TranslatesAttributes $model, array $data, ?string $attribute, string $targetLanguage, $rules = null, ?string $extraPrompt = null): array
    {
        $rules = $model?->getTranslatableAttributeRulesFor($attribute ?? throw new \InvalidArgumentException('Attribute is required')) ?? $rules;

        if (in_array('*', $rules, true)) {
            return static::translateAllStringsInArray($data, $targetLanguage, $extraPrompt);
        }

        collect($rules)
            ->filter(fn ($rule) => str_starts_with($rule, '!'))
            ->map(fn ($rule) => ltrim($rule, '!'))
            ->each(fn ($key) => \Illuminate\Support\Arr::forget($data, $key));

        collect($rules)
            ->filter(fn ($rule) => str_starts_with($rule, '*'))
            ->each(function ($rule) use (&$data, $targetLanguage, $extraPrompt) {
                $key = ltrim($rule, '*');
                $data = static::translateAllKeysValuesFor($data, $key, $targetLanguage, $extraPrompt);
            });

        collect($rules)
            ->reject(fn ($rule) => str_starts_with($rule, '!'))
            ->reject(fn ($rule) => str_starts_with($rule, '*'))
            ->each(function ($path) use (&$data, $targetLanguage, $extraPrompt) {
                $segments = explode('.', $path);
                if (count($segments) === 1) {
                    $data = static::translateAllByKey($data, $segments[0], $targetLanguage, $extraPrompt);
                } else {
                    $data = static::translatePath($data, $segments, $targetLanguage, $extraPrompt);
                }
            });

        return $data;
    }

    protected static function translateAllKeysValuesFor(array $data, string $targetKey, string $targetLanguage, ?string $extraPrompt = null): array
    {
        foreach ($data as $key => $value) {
            if ($key === $targetKey && is_array($value)) {
                foreach ($value as $innerKey => $innerValue) {
                    if (is_string($innerValue) || is_numeric($innerValue)) {
                        $value[$innerKey] = static::translate($innerValue, $targetLanguage, $extraPrompt);
                    } elseif (is_array($innerValue)) {
                        $value[$innerKey] = static::translateAllStringsInArray($innerValue, $targetLanguage, $extraPrompt);
                    }
                }

                $data[$key] = $value;
            } elseif (is_array($value)) {
                $data[$key] = static::translateAllKeysValuesFor($value, $targetKey, $targetLanguage, $extraPrompt);
            }
        }

        return $data;
    }

    protected static function translateAllByKey(array $data, string $key, string $targetLanguage, ?string $extraPrompt = null): array
    {
        return collect($data)->map(function ($value, $k) use ($key, $targetLanguage, $extraPrompt) {
            if (is_array($value)) {
                return static::translateAllByKey($value, $key, $targetLanguage, $extraPrompt);
            }

            if ($k === $key && (is_string($value) || is_numeric($value))) {
                return static::translate($value, $targetLanguage, $extraPrompt);
            }

            return $value;
        })->toArray();
    }

    protected static function translateKeyAtRoot(array $data, string $key, string $targetLanguage, ?string $extraPrompt = null): array
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

        $data[$key] = static::translate($value, $targetLanguage, $extraPrompt);

        return $data;
    }

    protected static function translatePath(array $data, array $segments, string $targetLanguage, ?string $extraPrompt = null): array
    {
        if ($segments === []) {
            return $data;
        }

        $segment = array_shift($segments);

        if ($segment === '*') {
            foreach ($data as $key => $item) {
                if (is_array($item)) {
                    $data[$key] = static::translatePath($item, $segments, $targetLanguage, $extraPrompt);
                } else {
                    if (is_string($item) || is_numeric($item)) {
                        $data[$key] = static::translate($item, $targetLanguage, $extraPrompt);
                    } else {
                        continue;
                    }
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
                $data[$segment] = static::translate($value, $targetLanguage, $extraPrompt);
            }

            return $data;
        }

        if (is_array($data[$segment])) {
            $data[$segment] = static::translatePath($data[$segment], $segments, $targetLanguage, $extraPrompt);
        }

        return $data;
    }

    protected static function translateAllStringsInArray(array $data, string $targetLanguage, ?string $extraPrompt = null): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = static::translateAllStringsInArray($value, $targetLanguage, $extraPrompt);

                continue;
            }

            if (! is_string($value) && ! is_numeric($value)) {
                continue;
            }

            $data[$key] = static::translate($value, $targetLanguage, $extraPrompt);
        }

        return $data;
    }
}
