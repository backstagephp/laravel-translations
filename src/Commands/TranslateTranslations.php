<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Jobs\TranslateKeys;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Console\Command;

class TranslateTranslations extends Command
{
    protected $signature = 'translations:translate
                            {--all : Translate language strings for all languages} 
                            {--lang= : Translate language strings for a specific language}
                            {--update : Update and overwrite existing translations}';

    protected $description = 'Translate language strings using the configured driver';

    public function handle()
    {
        $all = $this->option('all');
        $lang = $this->option('lang');

        $this->invalidOptionCombination($all, $lang);

        if ($this->option('update')) {
            $translations = Translation::all();

            if ($lang) {
                $translations = Translation::where('code', $code)->get();

                if ($translations->isEmpty()) {
                    $this->fail("No translations found with the code: {$code}");

                    return;
                }
            }

            $translations->each(function ($translation) {
                $translation->update([
                    'translated_at' => null,
                ]);
            });
        }

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

        TranslateKeys::dispatch();

        $this->info('All languages processed successfully.');
    }

    protected function handleSpecificLanguage(?string $lang): void
    {
        $this->info("Translating imports for language: $lang...");

        $language = Language::where('code', $lang)->first();

        if (! $language) {
            $this->fail("Language '$lang' not found.");
        }

        TranslateKeys::dispatch($language);

        $this->info("Language {$language->locale} processed successfully.");
    }
}
