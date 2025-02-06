<?php

namespace Vormkracht10\LaravelTranslations\Commands;

use Illuminate\Console\Command;
use Vormkracht10\LaravelTranslations\Jobs\TranslateKeys;
use Vormkracht10\LaravelTranslations\Models\Language;
use Vormkracht10\LaravelTranslations\Models\Translation;

class TranslateImports extends Command
{
    protected $signature = 'translations:redo-translate-keys 
                            {--all : Translate imports for all languages} 
                            {--lang= : Translate imports for a specific language}';

    protected $description = 'Translate imports for a specific language or all languages';

    public function handle()
    {
        $all = $this->option('all');
        $lang = $this->option('lang');

        $this->invalidOptionCombination($all, $lang);

        $all ? $this->handleAllLanguages() : $this->handleSpecificLanguage($lang);
    }

    protected function invalidOptionCombination(bool $all, ?string $lang): void
    {
        if ($all && $lang) {
            $this->fail('You cannot use both --all and --lang options together. Please choose one.');
        }

        if (! $all && ! $lang) {
            $this->fail('Please provide a language using the --lang option or use the --all flag.');
        }
    }

    protected function handleAllLanguages(): void
    {
        $this->info('Translating imports for all languages...');

        Translation::all()->each(function ($translation) {
            $translation->update([
                'translated_at' => null,
            ]);
        });

        TranslateKeys::dispatch();

        $this->info('All languages processed successfully.');
    }

    protected function handleSpecificLanguage(?string $lang): void
    {
        $this->info("Translating imports for language: $lang...");

        $language = Language::where('locale', $lang)->first();

        Translation::where('locale', $language->locale)->get()->each(function ($translation) {
            $translation->update([
                'translated_at' => null,
            ]);
        });


        if (! $language) {
            $this->fail("Language '$lang' not found.");
        }

        TranslateKeys::dispatch($language);

        $this->info("Language {$language->locale} processed successfully.");
    }
}
