<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTranslatedAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, string $locale): mixed
    {
        if (! in_array($attribute, $model->getTranslatableAttributes())) {
            return $model->getAttribute($attribute);
        }

        $locale = $locale ?: App::getLocale();

        $value =  $model->translatableAttributes()
            ->where('attribute', $attribute)
            ->where('code', $locale)
            ->first()
            ?->translated_attribute ?? null;

        if (is_null($value)) {
            return $model->getAttribute($attribute);
        }

        $resultingAttribute = static::getMutatedAttribute($model, $attribute, $value);

        return $resultingAttribute;
    }

    public static function getMutatedAttribute(Model $model,  $resultingAttribute, $resultingAttributeValue): mixed
    {
        $model->setRawAttributes([$resultingAttribute => $resultingAttributeValue] + $model->getAttributes());

        return $model->getAttribute($resultingAttribute);
    }

    public static function getReversedMutatedAttribute(Model $model, string $key, mixed $value): mixed
    {
        $clone = clone $model;

        $clone->setAttribute($key, $value);

        return $clone->getAttributes()[$key] ?? null;
    }
}
