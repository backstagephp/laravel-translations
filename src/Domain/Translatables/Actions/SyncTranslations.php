<?php

namespace Backstage\Translations\Laravel\Domain\Translatables\Actions;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncTranslations
{
    use AsAction;

    /**
     * @param  HasTranslatableAttributes|Model  $model
     */
    public function handle(object $model, ?\Illuminate\Console\OutputStyle $output = null): void
    {
        /**
         * @var array $designatedAttributes
         */
        $designatedAttributes = $model->getTranslatableAttributes();

        foreach ($designatedAttributes as $attribute) {
            $model->translateAttributeForAllLanguages($attribute);

            if ($output) {
                $output->progressAdvance();
            }
        }
    }
}
