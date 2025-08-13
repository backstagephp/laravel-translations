<?php

namespace Backstage\Translations\Laravel\Caches;

use Backstage\PermanentCache\Laravel\Cached;
use Backstage\PermanentCache\Laravel\Scheduled;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslationStringsCache extends Cached implements Scheduled, ShouldQueue
{
    protected $store = 'file:translations';

    public function run(): array
    {
        $locales = Translation::query()->distinct()->pluck('code');
        $groups = Translation::query()->distinct()->pluck('group');
        $namespaces = Translation::query()->distinct()->pluck('namespace');

        return $locales->mapWithKeys(function (string $locale) use ($groups, $namespaces) {
            return [
                $locale => $groups->mapWithKeys(function (?string $group) use ($locale, $namespaces) {
                    return [
                        $group ?? '*' => $namespaces->mapWithKeys(function (?string $namespace) use ($locale, $group) {
                            return [
                                $namespace => static::getTranslations($locale, $group ?? '*', $namespace),
                            ];
                        })->all(),
                    ];
                })->all(),
            ];
        })->all();
    }

    protected static function getTranslations(string $locale, string $group = '*', ?string $namespace = null): array
    {
        $query = Translation::query()->where('code', $locale);

        if ($namespace !== null && $namespace !== '*') {
            $query->where('namespace', $namespace);
        }

        if ($group !== '*') {
            $query->where('group', $group);
        }

        return $query->pluck('text', 'key')->toArray();
    }

    public static function schedule($callback)
    {
        return $callback->everyTenMinutes(); // Adjust as needed
    }
}
