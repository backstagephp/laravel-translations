<?php

namespace Backstage\Translations\Laravel\Listners;

use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleLanguageCodeChanges implements ShouldQueue
{
    public function handle(LanguageCodeChanged $event)
    {
        Translation::where('code', $event->language->getOriginal('code'))
            ->get()
            ->each(function (Translation $translation) use ($event) {
                $translation->code = $event->language->getAttribute('code');

                $translation->translated_at = null;

                $translation->save();
            });
    }
}
