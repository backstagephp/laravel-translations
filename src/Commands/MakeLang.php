<?php

namespace Vormkracht10\LaravelTranslations\Commands;

use Illuminate\Console\Command;
use Vormkracht10\LaravelTranslations\Models\Language;

use function Laravel\Prompts\text;

class MakeLang extends Command
{
    protected $signature = 'translations:lang {locale?} {label?}';

    protected $description = 'Create a new language type';

    public function handle()
    {
        $locale = $this->argument('locale') ?? text('Enter locale');
        $label = $this->argument('label') ?? text('Enter label');
        $this->info("Creating language type: $locale with label: $label");

        $this->createLanguage($locale, $label);
    }

    protected function createLanguage($locale, $label)
    {
        $lang = Language::where('locale', $locale)->first();

        if ($lang) {
            $this->info("Language $locale already exists");

            return $lang;
        }

        Language::create(['locale' => $locale, 'label' => $label]);

        $this->info("Language $locale with label $label created");

        $this->info('Language type created');
    }
}
