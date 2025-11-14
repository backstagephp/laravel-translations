<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Locale;
use Lorisleiva\Actions\Concerns\AsAction;

class PushTranslatedAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, mixed $translation, string $locale): void
    {
        /**
         * @var bool $localExists
         */
        $localExists = Language::query()->where('code', $locale)->exists();

        if (! $localExists) {
            Language::query()->create([
                'code' => $locale,
                'name' => Locale::getDisplayName($locale),
            ]);
        }

        /**
         * @var mixed $reverseMutatedAttributeValue
         */
        $reverseMutatedAttributeValue = GetTranslatedAttribute::getReversedMutatedAttribute(
            $model,
            $attribute,
            $translation
        );

        if (is_array($reverseMutatedAttributeValue)) {
            $reverseMutatedAttributeValue = json_encode($reverseMutatedAttributeValue, JSON_UNESCAPED_UNICODE);
        }

        static::modifyTranslatedAttributeValue($model, $attribute, $reverseMutatedAttributeValue, $locale);
    }

    public static function modifyTranslatedAttributeValue(Model $model, string $attribute, mixed $reverseMutatedAttributeValue, string $locale): void
    {
        $model->translatableAttributes()->updateOrCreate([
            'translatable_type' => get_class($model),
            'translatable_id' => $model->getKey(),
            'attribute' => $attribute,
            'code' => $locale,
        ], [
            'translated_attribute' => $reverseMutatedAttributeValue,
            'translated_at' => now(),
        ]);
    }

    public static function modifyOriginalAttributeValue(Model $model, string $attribute, mixed $value): void
    {
        $model->updateQuietly([
            $attribute => $value,
        ]);
    }
}
