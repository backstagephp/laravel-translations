<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

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
        /**
         * @var array $translatableAttributes
         */
        $translatableAttributes = $model->getTranslatableAttributes();

        /**
         * @var bool $isTranslatableAttribute
         */
        $isTranslatableAttribute = in_array($attribute, $translatableAttributes);

        return $isTranslatableAttribute;
    }
}
