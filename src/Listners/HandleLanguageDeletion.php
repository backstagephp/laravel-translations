<?php

namespace Backstage\Translations\Laravel\Listners;

use Backstage\Translations\Laravel\Models\Translation;
use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleLanguageDeletion implements ShouldQueue
{
    public function handle(LanguageDeleted $event)
    {
        Translation::where('code', $event->language->code)->delete();
    }
}