<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Jobs\ScanTranslationStrings;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Console\Command;

class TranslationsScan extends Command
{
    protected $signature = 'translations:scan';

    protected $description = 'Scan Laravel translations';

    public function handle()
    {
        if (Language::count() === 0) {
            Language::create([
                'code' => app()->getLocale(),
                'name' => localized_language_name(app()->getLocale()),
                'native' => localized_language_name(app()->getLocale()),
                'active' => true,
            ]);
        }

        Language::active()->get()->each(function (Language $language) {
            ScanTranslationStrings::dispatchSync($language);
        });
    }
}
