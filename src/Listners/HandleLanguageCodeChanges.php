<?php

namespace Backstage\Translations\Laravel\Listners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Backstage\Translations\Laravel\Models\Translation;
use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Models\TranslatableCodeString;

class HandleLanguageCodeChanges implements ShouldQueue
{
    public function handle(LanguageCodeChanged $event)
    {
        TranslatableCodeString::query()
            ->whereHas('translatableAttributes', function ($query) use ($event) {
                $query->where('code', $event->language->getOriginal('code'));
            })
            ->get()
            ->each(function (TranslatableCodeString $translation) use ($event) {
                $translation->translatableAttributes()
                    ->where('code', $event->language->getOriginal('code'))
                    ->delete();

                $translation->delete();
            });
    }
}
