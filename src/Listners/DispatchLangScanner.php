<?php

namespace Backstage\Translations\Laravel\Listners;

use Backstage\Translations\Laravel\Events\LanguageCreated;
use Backstage\Translations\Laravel\Jobs\ScanTranslationStrings;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchLangScanner implements ShouldQueue
{
    public function handle(LanguageCreated $event)
    {
        dispatch(new ScanTranslationStrings($event->language));
    }
}
