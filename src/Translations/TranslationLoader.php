<?php

namespace Vormkracht10\LaravelTranslations\Translations;

use Illuminate\Translation\FileLoader;
use Vormkracht10\LaravelTranslations\Models\Translation;

class TranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null)
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (! is_null($namespace) && $namespace !== '*') {
            return $fileTranslations;
        }

        $dbTranslations = cache()->tags('translations')->rememberForever('translations-'.strtolower($group).'-'.$locale, function () use ($locale) {
            return Translation::select('key', 'text')
                ->where('locale', $locale)
                ->pluck('text', 'key')
                ->toArray();
        });

        if ($dbTranslations) {
            return $dbTranslations + $fileTranslations;
        } else {
            return $fileTranslations;
        }
    }

    public function addNamespace($namespace, $hint) {}

    public function addJsonPath($path) {}

    public function namespaces() {}
}
