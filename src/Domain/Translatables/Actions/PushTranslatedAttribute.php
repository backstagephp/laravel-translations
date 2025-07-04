<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Locale;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;

class PushTranslatedAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, mixed $translation, string $locale): void
    {
        $localExists = Language::query()->where('code', $locale)->exists();

        if (! $localExists) {
            Language::query()->create([
                'code' => $locale,
                'name' => Locale::getDisplayName($locale),
            ]);
        }

        $reverseMutatedAttributeValue = GetTranslatedAttribute::getReversedMutatedAttribute(
            $model,
            $attribute,
            $translation
        );


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
}
