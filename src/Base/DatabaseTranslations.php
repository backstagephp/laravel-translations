<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Models\Translation;

class DatabaseTranslations
{
    protected array $loaded = [];

    /**
     * Singleton memo: each locale/group/namespace scope hits the database once
     * per container lifecycle. Translation saved/deleted events forget the
     * instance, so the next resolve starts fresh.
     */
    public function get(string $locale, string $group, ?string $namespace = null): array
    {
        return $this->loaded[$locale][$namespace ?? ''][$group] ??= $this->getTranslationsFromDatabase($locale, $group, $namespace);
    }

    protected function getTranslationsFromDatabase(string $locale, string $group, ?string $namespace = null): array
    {
        $translations = Translation::select('key', 'text', 'namespace', 'group');

        if ($namespace !== '*') {
            $translations->where('namespace', $namespace);
        }

        if ($group !== '*') {
            $translations->where('group', $group);
        }

        return $translations->where(fn ($query) => $query->where('code', 'LIKE', $locale . '_%')->orWhere('code', $locale))
            ->pluck('text', 'key')
            ->toArray();
    }
}
