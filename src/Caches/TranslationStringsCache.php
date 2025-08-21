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
        return static::collect();
    }

    public static function collect()
    {
        $translations = Translation::query()
            ->select(['code', 'group', 'namespace', 'key', 'text'])
            ->get();

        return $translations
            ->groupBy('code')
            ->map(function ($byLocale) {
                return $byLocale->groupBy(fn ($row) => $row->group ?? '*')
                    ->map(function ($byGroup) {
                        return $byGroup->groupBy(fn ($row) => $row->namespace)
                            ->map(function ($byNamespace) {
                                return $byNamespace->mapWithKeys(fn ($row) => [
                                    $row->key => $row->text,
                                ]);
                            });
                    });
            })
            ->toArray();
    }

    public static function schedule($callback)
    {
        return $callback->everyTenMinutes(); // Adjust as needed
    }
}
