<?php

namespace Vormkracht10\LaravelTranslations\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Vormkracht10\LaravelTranslations\Models\Language;
use Vormkracht10\LaravelTranslations\Models\Translation;

class ScanTranslatableKeys implements ShouldQueue
{
    public function handle()
    {
        App::singleton('translator', function () {
            return new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\FileLoader(new \Illuminate\Filesystem\Filesystem, 'resources/lang'),
                'en'
            );
        });

        $functions = $this->getTranslationFunctions();
        $paths = $this->getPaths();

        $translations = $this->extractTranslations($paths, $functions);

        $locales = $this->getLocales();

        $baseLocale = App::getLocale();
        $translations = collect($translations)->unique();

        $localizedTranslations = $this->mapTranslations($translations, $locales);

        App::setLocale($baseLocale);

        $this->storeTranslations($localizedTranslations);
    }

    protected function getTranslationFunctions(): array
    {
        return [
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice',
            '__',
        ];
    }

    protected function getPaths(): array
    {
        $paths = config('translations.scan.paths');

        return $paths;
    }

    protected function extractTranslations(array $paths, array $functions): array
    {
        $translations = [];

        foreach ($paths as $path) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

            foreach ($files as $file) {
                if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
                    $content = file_get_contents($file->getRealPath());

                    foreach ($functions as $function) {
                        if (preg_match_all("/$function\\(['\"](.+?)['\"]\\)/", $content, $matches)) {
                            $translations = array_merge($translations, $matches[1]);
                        }
                    }
                }
            }
        }

        return $translations;
    }

    protected function getLocales(): \Illuminate\Support\Collection
    {
        $locales = Language::pluck('locale');

        if ($locales->isEmpty()) {
            return collect();
        }

        return $locales;
    }

    protected function mapTranslations($translations, $locales): \Illuminate\Support\Collection
    {
        return $translations->flatMap(function ($translation) use ($locales) {
            return $locales->map(function ($locale) use ($translation) {
                App::setLocale($locale);
                App::setFallbackLocale($locale);

                return [
                    'locale' => $locale,
                    'key' => $translation,
                    'text' => __($translation, [], $locale),
                    'namespace' => $this->getNamespace($translation),
                ];
            });
        });
    }

    protected function storeTranslations($translations): void
    {
        $translations->each(function ($translation) {
            if (! is_array($translation['text'])) {
                $text = $translation['text'];

                if ($text === null) {
                    $text = $translation['key'];
                }

                Translation::updateOrCreate([
                    'group' => null,
                    'locale' => $translation['locale'],
                    'key' => str_replace([$translation['namespace'], '::'], '', $translation['key']),
                    'namespace' => $translation['namespace'],
                ], [
                    'text' => $translation['text'] ?? $translation['key'],
                ]);
            }
        });
    }

    protected function getNamespace(string $translation): string
    {
        return str_contains($translation, '::')
            ? explode('::', $translation)[0]
            : '*';
    }
}
