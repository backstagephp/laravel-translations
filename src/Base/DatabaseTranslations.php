<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Models\Translation;

class DatabaseTranslations
{
    protected array $loaded = [];

    /**
     * Container-scoped memo: each locale/group/namespace scope hits the
     * database once per request, Octane included.
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
