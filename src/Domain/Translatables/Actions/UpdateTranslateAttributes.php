<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTranslateAttributes
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, array $attributes): void
    {
        /**
         * @var \Illuminate\Database\Eloquent\Collection $languages
         */
        $languages = Language::all();

        foreach ($languages as $language) {
            foreach ($attributes as $attribute) {
                $model->translateAttribute($attribute, $language->code, true);
            }
        }
    }
}
