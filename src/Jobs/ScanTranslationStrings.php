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
use Illuminate\Support\Facades\Lang;

class ScanTranslationStrings implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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

                if (! $this->redo) {
                    if ($data['namespace'] !== '*') {
                        $data['text'] = Lang::get(key: $translation['key'], locale: $locale);
                    } else {
                        $data['text'] = Lang::get(key: $translation['key'], locale: $locale);
                    }

                    return $data;
                }

                $oldTranslation = Translation::query()
                    ->where('key', $translation['key'])
                    ->where('group', $translation['group'])
                    ->where('namespace', $translation['namespace'] ?? '*')
                    ->where('code', $baseLocale)
                    ->first();

                if ($oldTranslation) {
                    $data['text'] = $oldTranslation->text;
                } else {
                    $data['text'] = Lang::get(key: $translation['key'], locale: $locale);
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
                    'translated_at' => static::translationIsTranslated($translation) ? now() : null,
                ]);
            }
        });

        TranslationStringsCache::update();
    }

    protected static function translationIsTranslated(array $translation): bool
    {
        if ($translation['namespace'] === '*') {
            return false;
        }

        if ($translation['key'] === $translation['text']) {
            return false;
        }

        return true;
    }
}
