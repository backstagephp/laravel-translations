<?php

namespace Backstage\Translations\Laravel\Observers;

use Backstage\Translations\Laravel\Events\LanguageAdded;
use Backstage\Translations\Laravel\Events\LanguageCodeChanged;
use Backstage\Translations\Laravel\Events\LanguageDeleted;
use Backstage\Translations\Laravel\Models\Language;

class LanguageObserver
{
    public function creating(Language $language)
    {
        if (! Language::where('default', true)->exists()) {
            $language->default = true;
        }

        if (! Language::where('active', true)->exists()) {
            $language->active = true;
        }
    }

    public function created(Language $language)
    {
        event(new LanguageAdded($language));
    }

    public function updated(Language $language)
    {
        if ($language->wasChanged('active') && ! $language->active) {
            if (Language::where('active', true)->count() == 0) {
                Language::where('code', $language->code)
                    ->update([
                        'active' => true,
                    ]);
            }
        }

        if ($language->wasChanged('default') && ! $language->active) {
            Language::where('code', $language->code)
                ->update([
                    'default' => false,
                ]);

            return;
        }

        $defaultExists = Language::where('default', true)->exists();

        if ($language->default) {
            Language::where('code', '!=', $language->code)->update([
                'default' => false,
            ]);
        } elseif (! $language->default && ! $defaultExists) {
            Language::where('code', $language->code)
                ->update([
                    'default' => true,
                ]);
        }

        if ($language->wasChanged('code')) {
            event(new LanguageCodeChanged($language));
        }
    }

    public function deleted(Language $language)
    {
        if ($language->default && Language::count() > 0) {
            Language::where('code', '!=', $language->code)
                ->first()
                ->update([
                    'default' => true,
                ]);
        }

        event(new LanguageDeleted($language));
    }
}
