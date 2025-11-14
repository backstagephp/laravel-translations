<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTranslatedAttributes
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle($model, string $locale): array
    {
        /**
         * @var array $translatableAttributes
         */
        $translatableAttributes = $model->getTranslatableAttributes();

        /**
         * @var array $translatedAttributes
         */
        $translatedAttributes = collect($translatableAttributes)
            ->values()
            ->mapWithKeys(function ($attribute) use ($model, $locale) {
                return $model->getTranslatedAttribute($attribute, $locale) !== null
                    ? [$attribute => $model->getTranslatedAttribute($attribute, $locale)]
                    : [];
            })->toArray();

        return array_merge(
            $translatedAttributes
        );
    }
}
