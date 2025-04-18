<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (
            ! Schema::hasTable((new Translation)->getTable()) ||
            (! is_null($namespace) && $namespace !== '*')
        ) {
            return $fileTranslations;
        }

        return array_replace_recursive($fileTranslations, $this->getTranslationsFromDatabase($locale, $group, $namespace));
    }

    protected function getTranslationsFromDatabase(string $locale, string $group, ?string $namespace = null): array
    {
        $translations = Translation::select('key', 'text');

        if ($namespace !== '*') {
            $translations->where('namespace', $namespace);
        }

        if ($group !== '*') {
            $translations->where('group', $group);
        }

        return $translations->where(fn ($query) => $query->where('code', 'LIKE', $locale.'_%')->orWhere('code', $locale))
            ->pluck('text', 'key')
            ->toArray();
    }
}
