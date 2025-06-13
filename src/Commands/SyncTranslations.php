<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\TranslatableCodeString;
use Illuminate\Console\Command;

class SyncTranslations extends Command
{
    protected $signature = 'translations:sync';

    protected $description = 'Sync translations for all translatable models';

    public function handle(): void
    {
        $translationableModels = config('translations.eloquent.translatable-models', [
            TranslatableCodeString::class,
        ]);

        foreach ($translationableModels as $model) {
            $model::all()->each(function (TranslatesAttributes $item) {
                $item->syncTranslations();
            });
        }
    }
}
