<?php

namespace Vormkracht10\LaravelTranslations\Commands;

use Illuminate\Console\Command;
use Vormkracht10\LaravelTranslations\Jobs\ScanTranslatableKeys;
use Vormkracht10\LaravelTranslations\Models\Language;

class ScanLang extends Command
{
    protected $signature = 'translations:scan';

    protected $description = 'Scan Laravel translations';

    public function handle()
    {
        if (Language::count() === 0) {
            $this->fail('No languages found. Please create a language first.');
        }

        Language::all()->each(function (Language $language) {
            dispatch_sync(new ScanTranslatableKeys($language));
        });
    }
}
