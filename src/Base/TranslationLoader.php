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

        if (! static::checkTableExists() || (! is_null($namespace) && $namespace !== '*')) {
            return $fileTranslations;
        }

        return array_replace_recursive($fileTranslations, once(fn() => static::getTranslationsFromDatabase($locale, $group, $namespace)));
    }

    protected static function getTranslationsFromDatabase(string $locale, string $group, ?string $namespace = null): array
    {
        $cachedData = TranslationStringsCache::get();

        if (! isset($cachedData[$locale][$group][$namespace])) {
            return [];
        }

        $translations = $cachedData[$locale][$group][$namespace];

        return collect($translations)->mapWithKeys(function ($text, $key) {
            return [$key => $text];
        })->toArray();
    }

    protected static function checkTableExists(): bool
    {
        if (!app()->isProduction()) {
            return Schema::hasTable((new Translation)->getTable());
        }

        return Cache::remember('translations:table_exists', 60, function () {
            return Schema::hasTable((new Translation)->getTable());
        });
    }
}
