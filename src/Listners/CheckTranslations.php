<?php

namespace Backstage\Translations\Laravel\Listners;

use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Events\LanguageUpdated;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckTranslations implements ShouldQueue
{
    public function handle(LanguageUpdated $event)
    {
        Translation::where('code', $event->language->getOriginal('code'))
            ->get()
            ->each(fn($translation) => $translation->update(['code' => $event->language->getAttribute('code')]));
    }
}
