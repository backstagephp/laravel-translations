<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class TranslationsAddLanguage extends Command
{
    protected $signature = 'translations:languages:add {code?} {label?}';

    protected $description = 'Create a new language type';

    public function handle()
    {
        $locale = $this->argument('code') ?? text('Enter locale');
        $label = $this->argument('name') ?? text('Enter label');
        $this->info("Creating language type: $locale with label: $label");

        $this->createLanguage($locale, $label);
    }

    protected function createLanguage($locale, $label)
    {
        $lang = Language::where('code', $locale)->first();

        if ($lang) {
            $this->info("Language $locale already exists");

            return $lang;
        }

        Language::create(['code' => $locale, 'name' => $label]);

        $this->info("Language $locale with label $label created");

        $this->info('Language type created');
    }
}
