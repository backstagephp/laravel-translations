<?php

namespace Backstage\Translations\Laravel\Base;

use Illuminate\Translation\Translator as BaseTranslator;

class Translator extends BaseTranslator
{
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $locale = $locale ?: $this->locale;

        $this->load('*', '*', $locale);

        if (is_string($key)) {
            $line = isset($this->loaded['*']['*'][$locale][$key]) && ! is_array($this->loaded['*']['*'][$locale][$key])
                        ? $this->loaded['*']['*'][$locale][$key] : null;

            return $this->makeReplacements($line ?: $key, $replace);
        } else {
            return $key;
        }
    }
}
