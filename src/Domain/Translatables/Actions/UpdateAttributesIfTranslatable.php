<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAttributesIfTranslatable
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, array $translatableAttributes): void
    {
        foreach ($translatableAttributes as $attribute) {
            $model->translateAttributeForAllLanguages(attribute: $attribute, overwrite: true);
        }
    }
}
