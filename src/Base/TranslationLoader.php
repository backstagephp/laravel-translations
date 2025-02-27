<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null)
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (
            ! Schema::hasTable((new Translation)->getTable()) ||
            ! is_null($namespace) && $namespace !== '*'
        ) {
            return $fileTranslations;
        }

        return array_replace_recursive($fileTranslations, $this->getTranslationsFromDatabase($locale, $group, $namespace));
    }

    protected function getTranslationsFromDatabase(
        string $locale,
        string $group,
        ?string $namespace = null
    ): array {
        return Translation::select('key', 'text')
            ->where('group', $group)
            ->where('namespace', $namespace)
            ->where(fn ($query) => $query->where('code', 'LIKE', $locale.'_%')->orWhere('code', $locale))
            ->pluck('text', 'key')
            ->toArray();
    }
}
