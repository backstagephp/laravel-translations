<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Facades\Translator;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute, string $targetLanguage, bool $overwrite = false): string
    {
        $originalValue = $model->getAttribute($attribute);

        if (! $overwrite) {
            $isTranslated = $model->translatableAttributes()
                ->getQuery()
                ->where('attribute', $attribute)
                ->where('code', $targetLanguage)
                ->exists();

            if ($isTranslated) {
                return $model->getTranslatedAttribute($attribute, $targetLanguage);
            }
        }

        $translated = Translator::with(config('translations.translators.default'))
            ->translate($originalValue, $targetLanguage);

        if ($translated === null) {
            $translated = $model->translatableAttributes()
                ->where('attribute', $attribute)
                ->where('code', $targetLanguage)
                ->value('translated_attribute');
        }

        $model->pushTranslateAttribute($attribute, $translated, $targetLanguage);

        return $translated ?: $originalValue;
    }
}
