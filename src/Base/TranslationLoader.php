<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (! static::checkTableExists() || ($namespace !== null && $namespace !== '*')) {
            return $fileTranslations;
        }

        return array_replace_recursive($fileTranslations, static::getTranslationsFromDatabase($locale, $group, $namespace));
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

        return $translations->where(fn ($query) => $query->where('code', 'LIKE', $locale.'_%')->orWhere('code', $locale))
            ->pluck('text', 'key')
            ->toArray();
    }

    protected static function checkTableExists(): bool
    {
        static $exists = null;

        if ($exists !== null) {
            return $exists;
        }

        $table = (new Translation)->getTable();

        if (! app()->isProduction()) {
            return $exists = Schema::hasTable($table);
        }

        return $exists = Cache::remember('translations:table_exists', 3600, fn () => Schema::hasTable($table));
    }
}
