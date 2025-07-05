<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttributesForAllLanguages
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model): array
    {
        /**
         * @var \Illuminate\Database\Eloquent\Collection $languages
         */
        $languages = Language::all();

        /**
         * @var \Illuminate\Database\Eloquent\Collection $translations
         */
        $translations = $languages->mapWithKeys(function (Language $language) use ($model) {
            return [$language->code => $model->translateAttributes($language->code)];
        });

        return $translations->toArray();
    }
}
