<?php

namespace Vormkracht10\LaravelTranslations\Jobs;

use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Queue\ShouldQueue;
use Vormkracht10\LaravelTranslations\Models\Language;
use Vormkracht10\LaravelTranslations\Models\Translation;
use Vormkracht10\LaravelTranslations\Domain\Actions\GetTranslatables;

class ScanTranslatableKeys implements ShouldQueue
{
    protected ?Language $locale;

    public function __construct(Language $locale = null)
    {
        $this->locale = $locale;
    }

    public function handle()
    {
        App::singleton('translator', function () {
            return new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\FileLoader(new \Illuminate\Filesystem\Filesystem, 'resources/lang'),
                'en'
            );
        });

        $translations = collect(GetTranslatables::run())->unique();

        $locales = $this->locale ? collect([$this->locale->locale]) : $this->getLocales();

        $baseLocale = App::getLocale();

        $localizedTranslations = $this->mapTranslations($translations, $locales);

        App::setLocale($baseLocale);

        $this->storeTranslations($localizedTranslations);
    }

    protected function getLocales(): \Illuminate\Support\Collection
    {
        return Language::pluck('locale');
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
            if (!is_array($translation['text'])) {
                Translation::updateOrCreate(
                    [
                        'group' => null,
                        'locale' => $translation['locale'],
                        'key' => $translation['key'],
                        'namespace' => $translation['namespace'],
                    ],
                    [
                        'text' => $translation['text'] ?? $translation['key'],
                        'last_scanned_at' => now(),
                    ]
                );
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
