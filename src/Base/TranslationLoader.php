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

        $dbTranslations = Translation::select('key', 'text')
            ->where('code', 'LIKE', $locale.'_%')
            ->orWhere('code', $locale)
            ->pluck('text', 'key')
            ->toArray();

        if ($dbTranslations) {
            return $dbTranslations + $fileTranslations;
        }

        return $fileTranslations;
    }
}
