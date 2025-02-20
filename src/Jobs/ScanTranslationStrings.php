<?php

namespace Backstage\Translations\Laravel\Jobs;

use Backstage\Translations\Laravel\Domain\Actions\GetTranslatables;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanTranslationStrings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $locales = $this->locale ? collect([$this->locale->code]) : $this->getLocales();

        $baseLocale = App::getLocale();

        $localizedTranslations = $this->mapTranslations($translations, $locales);

        App::setLocale($baseLocale);

        $this->storeTranslations($localizedTranslations);
    }

    protected function getLocales(): \Illuminate\Support\Collection
    {
        return Language::pluck('code');
    }

    protected function mapTranslations($translations, $locales): \Illuminate\Support\Collection
    {
        return $translations->flatMap(function ($translation) use ($locales) {
            return $locales->map(function ($locale) use ($translation) {
                $baseLocale = $locale;
                $locale = explode('_', $baseLocale)[0];

                App::setLocale($locale);
                App::setFallbackLocale($locale);

                $data = [
                    'code' => $baseLocale,
                    'group' => $translation['group'],
                    'key' => $translation['key'],
                    'namespace' => $translation['namespace'] ?? '*',
                ];

                if (! $this->redo) {
                    $data['text'] = Lang::get($translation['key'], [], $locale);

                    return $data;
                }

                $oldTranslation = Translation::where('key', $translation['key'])
                    ->where('group', $translation['group'])
                    ->where('namespace', $translation['namespace'] ?? '*')
                    ->where('code', $baseLocale)
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
                        'code' => $translation['code'],
                        'key' => $translation['key'],
                        'namespace' => $translation['namespace'],
                    ],
                    [
                        'text' => $translation['text'] ?? $translation['key'],
                    ]
                );
            }
        });
    }
}
