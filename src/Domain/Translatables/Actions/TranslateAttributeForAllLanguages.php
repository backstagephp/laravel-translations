<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttributeForAllLanguages
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, bool $overwrite = false): array
    {
        $languages = Language::all();

        $translations = $languages->mapWithKeys(function (Language $language) use ($attribute, $overwrite, $model) {
            return [$language->code => $model->translateAttribute($attribute, $language->code, $overwrite)];
        });

        return $translations->toArray();
    }
}
