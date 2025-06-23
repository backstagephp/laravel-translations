<?php

namespace Backstage\Translations\Laravel\Actions\Translatables;

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
    public function handle(object $model, string $attribute, string $locale): ?string
    {
        if (! in_array($attribute, $model->getTranslatableAttributes())) {
            return $model->getAttribute($attribute);
        }

        $locale = $locale ?: App::getLocale();

        return $model->translatableAttributes()
            ->where('attribute', $attribute)
            ->where('code', $locale)
            ->first()
            ?->translated_attribute ?? $model->getAttribute($attribute);
    }
}
