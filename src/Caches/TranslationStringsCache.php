<?php

namespace Backstage\Translations\Laravel\Caches;

use Backstage\PermanentCache\Laravel\Cached;
use Backstage\PermanentCache\Laravel\Scheduled;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatableCodeString;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslationStringsCache extends Cached implements Scheduled, ShouldQueue
{
    protected $store = 'redis:translations';

    public function run(): array
    {
        $locales = Language::all()->pluck('code')->toArray();

        $groups = TranslatableCodeString::distinct('group')->pluck('group')->toArray();

        $namespaces = TranslatableCodeString::distinct('namespace')->pluck('namespace')->toArray();

        $translations = collect($locales)->mapWithKeys(function ($locale) use ($groups, $namespaces) {
            return [
                $locale => collect($groups)->mapWithKeys(function ($group) use ($locale, $namespaces) {
                    $groupKey = $group ?? '*';

                    return [
                        $groupKey => collect($namespaces)->mapWithKeys(function ($namespace) use ($locale, $groupKey) {
                            return [
                                $namespace => $this->getTranslationsFromDatabase($locale, $groupKey, $namespace),
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        })->toArray();

        return $translations;
    }

    protected function getTranslationsFromDatabase(string $locale, string $group = '*', ?string $namespace = null): array
    {
        $translations = TranslatableCodeString::all();

        if ($namespace !== '*') {
            $translations->where('namespace', $namespace);
        }

        if ($group !== '*') {
            $translations->where('group', $group);
        }

        $translations = $translations
            ->mapWithKeys(function (TranslatableCodeString $translation) use ($locale) {
                return [$translation->key => $translation->getTranslatedAttribute('text', $locale)];
            })
            ->toArray();

        return $translations;
    }

    public static function schedule($callback)
    {
        // Example of scheduling the cache refresh every 5 seconds, must be adjusted to every minue
        return $callback->everyTenSeconds();
    }
}
