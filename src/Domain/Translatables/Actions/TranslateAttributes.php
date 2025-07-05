<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Lorisleiva\Actions\Concerns\AsAction;

class TranslateAttributes
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, ?string $targetLanguage): array
    {
        /**
         * @var string $targetLanguage
         */
        $targetLanguage = $targetLanguage ?: App::getLocale();

        /**
         * @var array $translatedAttributes
         */
        $translatedAttributes = [];

        foreach ($model->getTranslatableAttributes() as $attribute) {
            $translatedAttributes[$attribute] = $model->translateAttribute($attribute, $targetLanguage);
        }

        return $translatedAttributes;
    }
}
