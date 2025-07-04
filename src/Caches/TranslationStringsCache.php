<?php

namespace Backstage\Translations\Laravel\Caches;

use Backstage\PermanentCache\Laravel\Cached;
use Backstage\PermanentCache\Laravel\Scheduled;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslationStringsCache extends Cached implements Scheduled, ShouldQueue
{
    protected $store = 'redis:translations';

    public function run(): array
    {
        /**
         * @var \Illuminate\Support\Collection $locales
         */
        $locales = Translation::distinct('code')->pluck('code');

        /**
         * @var \Illuminate\Support\Collection $groups
         */
        $groups = Translation::distinct('group')->pluck('group');

        /**
         * @var \Illuminate\Support\Collection $namespaces
         */
        $namespaces = Translation::distinct('namespace')->pluck('namespace');

        return $locales->mapWithKeys(fn ($locale) => [
            $locale => $groups->mapWithKeys(fn ($group) => [
                $group ?? '*' => $namespaces->mapWithKeys(
                    fn ($namespace) => [$namespace => static::getTranslations($locale, $group ?? '*', $namespace)]
                )->all(),
            ])->all(),
        ])->all();
    }

    protected static function getTranslations(string $locale, string $group = '*', ?string $namespace = null): array
    {
        /**
         * @var \Illuminate\Database\Eloquent\Builder $query
         */
        $query = Translation::query()
            ->where('code', $locale);

        if ($namespace !== '*') {
            $query->where('namespace', $namespace);
        }
        if ($group !== '*') {
            $query->where('group', $group);
        }

        $results = $query->select('key', 'text')->get();

        $mappedResult = $results->mapWithKeys(fn (Translation $t) => [$t->key => $t->text])->all();

        return $mappedResult;
    }

    public static function schedule($callback)
    {
        return $callback->everyTenMinutes(); // Adjust to everyTenMinutes() in real usage
    }
}
