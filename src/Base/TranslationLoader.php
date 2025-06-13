<?php

namespace Backstage\Translations\Laravel\Base;

use Backstage\Translations\Laravel\Models\TranslatableCodeString;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (
            ! Schema::hasTable((new TranslatableCodeString())->getTable()) ||
            (! is_null($namespace) && $namespace !== '*')
        ) {
            return $fileTranslations;
        }

        return array_replace_recursive($fileTranslations, cache()->rememberForever('translations', fn() => $this->getTranslationsFromDatabase($locale, $group, $namespace)));
    }

    protected function getTranslationsFromDatabase(string $locale, string $group, ?string $namespace = null): array
    {
        $translations = TranslatableCodeString::all();

        if ($namespace !== '*') {
            $translations->where('namespace', $namespace);
        }

        if ($group !== '*') {
            $translations->where('group', $group);
        }

        $translations = $translations
            ->mapWithKeys(function (TranslatableCodeString $translation) use ($locale) {
                return [$translation->key => $translation->getTranslatedAttribute('text', $locale)];
            })
            ->toArray();

        return $translations;
    }
}
