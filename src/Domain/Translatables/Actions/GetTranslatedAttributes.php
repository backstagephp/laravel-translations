<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTranslatedAttributes
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle($model, string $locale): array
    {
        $translatableAttributes = $model->getTranslatableAttributes();

        $translatedAttributes = collect($translatableAttributes)->mapWithKeys(function ($attribute) use ($model, $locale) {
            return $model->getTranslatedAttribute($attribute, $locale) !== null
                ? [$attribute => $model->getTranslatedAttribute($attribute, $locale)]
                : [];
        })->toArray();

        return array_merge(
            $translatedAttributes
        );
    }
}
