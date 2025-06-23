<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class PushTranslatedAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, string $translation, string $locale): void
    {
        $model->translatableAttributes()->updateOrCreate([
            'translatable_type' => get_class($model),
            'translatable_id' => $model->getKey(),
            'attribute' => $attribute,
            'code' => $locale,
        ], [
            'translated_attribute' => $translation,
            'translated_at' => now(),
        ]);
    }
}
