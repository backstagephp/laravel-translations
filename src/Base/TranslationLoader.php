<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Caches\TranslationStringsCache;
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

        return array_replace_recursive($fileTranslations, once(fn () => $this->getTranslationsFromDatabase($locale, $group, $namespace)));
    }

    protected function getTranslationsFromDatabase(string $locale, string $group, ?string $namespace = null): array
    {
        $cachedData = TranslationStringsCache::updateAndGet();

        if (! isset($cachedData[$locale][$group][$namespace])) {
            return [];
        }

        $translations = $cachedData[$locale][$group][$namespace];

        return collect($translations)->mapWithKeys(function ($text, $key) {
            return [$key => $text];
        })->toArray();
    }
}
