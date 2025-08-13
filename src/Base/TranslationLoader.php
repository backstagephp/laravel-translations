<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Caches\TranslationStringsCache;
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

    protected static function getTranslationsFromDatabase(string $locale, string $group, ?string $namespace = null): array
    {
        $cachedData = TranslationStringsCache::get();

        return $cachedData[$locale][$group][$namespace] ?? [];
    }

    protected static function checkTableExists(): bool
    {
        static $exists = null;

        if ($exists !== null) {
            return $exists;
        }

        $table = (new Translation())->getTable();

        if (! app()->isProduction()) {
            return $exists = Schema::hasTable($table);
        }

        return $exists = Cache::remember("translations:table_exists", 3600, fn() => Schema::hasTable($table));
    }
}
