<?php

namespace Backstage\Translations\Laravel\Actions\Translatables;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class IsTranslatableAttribute
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, string $attribute): bool
    {
        return in_array($attribute, $model->getTranslatableAttributes());
    }
}
