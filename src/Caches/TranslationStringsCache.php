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
        $locales = Translation::distinct('code')->pluck('code');
        $groups = Translation::distinct('group')->pluck('group');
        $namespaces = Translation::distinct('namespace')->pluck('namespace');

        return $locales->mapWithKeys(fn($locale) => [
            $locale => $groups->mapWithKeys(fn($group) => [
                $group ?? '*' => $namespaces->mapWithKeys(
                    fn($namespace) =>
                    [$namespace => $this->getTranslations($locale, $group ?? '*', $namespace)]
                )->all()
            ])->all()
        ])->dd()->all();
    }

    protected function getTranslations(string $locale, string $group = '*', ?string $namespace = null): array
    {
        $query = Translation::query()
            ->where('code', $locale);

        if ($namespace !== '*') $query->where('namespace', $namespace);
        if ($group !== '*') $query->where('group', $group);

        return $query->get()->mapWithKeys(fn(Translation $t) =>[$t->key => $t->text])->all();
    }

    public static function schedule($callback)
    {
        return $callback->everyTenSeconds(); // Adjust to everyMinute() in real usage
    }
}
