<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTranslatedAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, string $locale): mixed
    {
        if (! in_array($attribute, $model->getTranslatableAttributes()) || $locale === env('APP_LOCALE')) {
            return $model->getAttribute($attribute);
        }

        /**
         * @var string $locale
         */
        $locale = $locale ?: env('APP_LOCALE');

        /**
         * @var mixed $value
         */
        $value = $model->translatableAttributes()
            ->where('attribute', $attribute)
            ->where('code', $locale)
            ->first()
            ?->translated_attribute ?? null;

        if (is_null($value)) {
            return $model->getAttribute($attribute);
        }

        /**
         * @var mixed $resultingAttribute
         */
        $resultingAttribute = static::getMutatedAttribute($model, $attribute, $value);

        return $resultingAttribute;
    }

    public static function getMutatedAttribute(Model $model, $resultingAttribute, $resultingAttributeValue): mixed
    {
        $model->setRawAttributes([$resultingAttribute => $resultingAttributeValue] + $model->getAttributes());

        return $model->getAttribute($resultingAttribute);
    }

    public static function getReversedMutatedAttribute(Model $model, string $key, mixed $value): mixed
    {
        /**
         * @var Model $clonedModel
         */
        $clonedModel = clone $model;

        $clonedModel->setAttribute($key, $value);

        return $clonedModel->getAttributes()[$key] ?? null;
    }
}
