<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Jobs\TranslateKeys;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Console\Command;

class TranslateTranslations extends Command
{
    protected $signature = 'translations:translate
                            {--code= : Translate language strings for a specific language}
                            {--update : Update and overwrite existing translations}
                            {--queue : Dispatch the translation job to the queue instead of running it synchronously}';

    protected $description = 'Translate language strings using the configured driver';

    public function handle()
    {
        $languageCode = $this->option('code');

        if ($this->option('update')) {
            $translations = Translation::all();

            if ($languageCode) {
                $translations = Translation::where('code', $languageCode)->get();

                if ($translations->isEmpty()) {
                    $this->fail("No translations found with the code: {$languageCode}");

                    return;
                }
            }

            $translations->each(function ($translation) {
                $translation->update([
                    'translated_at' => null,
                ]);
            });
        }

        $languageCode ? $this->handleLanguage($languageCode) : $this->handleAllLanguages();
    }

    protected function handleAllLanguages(): void
    {
        if ($this->option('queue')) {
            $this->info('Dispatching translation job for all languages to the queue...');

            TranslateKeys::dispatch();

            $this->info('Translation job for all languages dispatched.');

            return;
        }

        $this->info('Translating imports for all languages...');

        TranslateKeys::dispatchSync();

        $this->info('All languages processed successfully.');
    }

    protected function handleLanguage(?string $code): void
    {
        $language = Language::where('code', $code)->first();

        if (! $language) {
            $this->fail("Language {$code} not found.");
        }

        if ($this->option('queue')) {
            $this->info("Dispatching translation job for language {$language->localizedLanguageName} to the queue...");

            TranslateKeys::dispatch($language);

            $this->info("Translation job for {$language->localizedLanguageName} dispatched.");

            return;
        }

        $this->info("Translating imports for language: {$language->localizedLanguageName}...");

        TranslateKeys::dispatchSync($language);

        $this->info("Language {$language->localizedLanguageName} processed successfully.");
    }
}
