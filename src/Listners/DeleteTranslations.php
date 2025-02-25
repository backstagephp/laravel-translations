<?php

namespace Backstage\Translations\Laravel\Listners;

use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteTranslations implements ShouldQueue
{
    public function handle(LanguageDeleted $event)
    {
        Translation::where('code', $event->language->code)->delete();
    }
}
