<?php

namespace Backstage\Translations\Laravel\Jobs;

use Backstage\Translations\Laravel\Caches\TranslationStringsCache;
use Backstage\Translations\Laravel\Domain\Scanner\Actions\FindTranslatables;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;

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
        $translations = collect(FindTranslatables::scan())->unique();

        $locales = $this->locale ? collect([$this->locale->code]) : $this->getLocales();

        $baseLocale = App::getLocale();

        $localizedTranslations = $this->mapTranslations($translations, $locales);

        App::setLocale($baseLocale);

        $this->storeTranslations($localizedTranslations);
    }

    protected function getLocales(): \Illuminate\Support\Collection
    {
        return Language::active()->pluck('code');
    }

    protected function mapTranslations($translations, $locales): \Illuminate\Support\Collection
    {
        return $translations->flatMap(function ($translation) use ($locales) {
            return $locales->map(function ($locale) use ($translation) {
                $baseLocale = $locale;
                $locale = explode('-', $baseLocale)[0];

                $data = [
                    'code' => $baseLocale,
                    'group' => $translation['group'],
                    'key' => $translation['key'],
                    'namespace' => $translation['namespace'] ?? '*',
                ];

                if ($data['namespace'] !== '*') {
                    $data['text'] = __(key: $translation['key'], locale: $locale);
                } else {
                    $data['text'] = __($translation['key'], [], $locale);
                }

                $oldTranslation = Translation::where('key', $translation['key'])
                    ->where('group', $translation['group'])
                    ->where('namespace', $translation['namespace'] ?? '*')
                    ->where('code', $baseLocale)
                    ->first();

                if ($oldTranslation) {
                    $data['text'] = $oldTranslation->text;
                } else {
                    $data['text'] = __($translation['key'], [], $locale);
                }

                return $data;
            });
        });
    }

    protected function storeTranslations($translations): void
    {
        Event::fake();

        $translations->each(function ($translation) {
            if (! is_array($translation['text'])) {
                Translation::firstOrCreate([
                    'group' => $translation['group'],
                    'code' => $translation['code'],
                    'key' => $translation['key'],
                    'namespace' => $translation['namespace'],
                ], [
                    'text' => $translation['text'] ?? $translation['key'],
                    'source_text' => $translation['text'] !== $translation['key'] ? $translation['text'] : null,
                    'translated_at' => $translation['text'] === __($translation['key'], [], $translation['code']) && $translation['namespace'] !== '*' ? now() : null,
                ]);
            }
        });

        TranslationStringsCache::update();
    }
}
