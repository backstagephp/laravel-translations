<?php

namespace Backstage\Translations\Laravel\Listners;

use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Models\TranslatableCodeString;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteTranslations implements ShouldQueue
{
    public function handle(LanguageDeleted $event)
    {
        $translatons = TranslatableCodeString::query()
            ->whereHas('translatableAttributes', function ($query) use ($event) {
                $query->where('code', $event->language->code);
            })
            ->get();

        foreach ($translatons as $translatable) {
            $translatable->translatableAttributes()
                ->delete();

            $translatable->delete();
        }
    }
}
