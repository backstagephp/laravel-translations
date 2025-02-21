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
            $this->fail('No languages found. Please create a language first.');
        }

        Language::active()->get()->each(function (Language $language) {
            ScanTranslationStrings::dispatchSync($language);
        });
    }
}
