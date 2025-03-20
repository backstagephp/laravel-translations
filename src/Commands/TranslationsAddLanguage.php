<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class TranslationsAddLanguage extends Command
{
    protected $signature = 'translations:languages:add {code?} {name?}';

    protected $description = 'Add a new language';

    public function handle()
    {
        $code = $this->argument('code') ?? text('Enter language code');
        $name = $this->argument('name') ?? text('Enter name');

        $this->createLanguage($code, $name);
    }

    protected function createLanguage($code, $name)
    {
        $lang = Language::where('code', $locale)->first();

        if ($lang) {
            $this->info("Language $locale already exists");

            return $lang;
        }

        Language::create([
            'code' => $locale,
            'name' => $name,
            'native' => localized_language_name($name),
        ]);

        $this->info("Language $name ($code) added.");
    }
}
