<?php

namespace Vormkracht10\LaravelTranslations\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Vormkracht10\LaravelTranslations\Domain\Actions\GetTranslatables;
use Vormkracht10\LaravelTranslations\Models\Language;
use Vormkracht10\LaravelTranslations\Models\Translation;

class ScanTranslatableKeys implements ShouldQueue
{
    protected ?Language $locale;

    public function __construct(?Language $locale = null, public bool $redo = false)
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

                $data =  [
                    'locale' => $locale,
                    'group' => $translation['group'],
                    'key' => $translation['key'],
                    'namespace' => $translation['namespace'] ?? '*',
                ];

                if (!$this->redo) {
                    $data['text'] = Lang::get($translation['key'], [], $locale);

                    return $data;
                }

                $oldTranslation = Translation::where('key', $translation['key'])
                    ->where('group', $translation['group'])
                    ->where('namespace', $translation['namespace'] ?? '*')
                    ->where('locale', $locale)
                    ->first();

                if ($oldTranslation) {
                    $data['text'] = $oldTranslation->text;
                } else {
                    $data['text'] = Lang::get($translation['key'], [], $locale);
                }

                return $data;
            });
        });
    }

    protected function storeTranslations($translations): void
    {
        $translations->each(function ($translation) {
            if (! is_array($translation['text'])) {
                Translation::updateOrCreate(
                    [
                        'group' => $translation['group'],
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
}
