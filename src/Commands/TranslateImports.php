<?php

namespace Vormkracht10\LaravelTranslations\Commands;

use Illuminate\Console\Command;
use Vormkracht10\LaravelTranslations\Jobs\TranslateKeys;
use Vormkracht10\LaravelTranslations\Models\Language;

class TranslateImports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:translate-keys 
                            {--all : Translate imports for all languages} 
                            {--lang= : Translate imports for a specific language}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate imports for a specific language or all languages';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all = $this->option('all');
        $lang = $this->option('lang');

        // Validate options
        $this->invalidOptionCombination($all, $lang);

        // Process translations
        $all ? $this->handleAllLanguages() : $this->handleSpecificLanguage($lang);
    }

    /**
     * Check for invalid option combinations.
     *
     * @return bool
     */
    private function invalidOptionCombination(bool $all, ?string $lang): void
    {
        if ($all && $lang) {
            $this->fail('You cannot use both --all and --lang options together. Please choose one.');
        }

        if (! $all && ! $lang) {
            $this->fail('Please provide a language using the --lang option or use the --all flag.');
        }
    }

    /**
     * Handle translation for all languages.
     */
    private function handleAllLanguages(): void
    {
        $this->info('Translating imports for all languages...');

        TranslateKeys::dispatch();

        $this->info('All languages processed successfully.');
    }

    /**
     * Handle translation for a specific language.
     */
    private function handleSpecificLanguage(?string $lang): void
    {
        $this->info("Translating imports for language: $lang...");

        $language = Language::where('locale', $lang)->first();

        if (! $language) {
            $this->fail("Language '$lang' not found.");
        }

        TranslateKeys::dispatch($language);

        $this->info("Language {$language->locale} processed successfully.");
    }
}
