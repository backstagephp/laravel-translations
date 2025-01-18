<?php

namespace Vormkracht10\LaravelTranslations\Drivers\Google;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Vormkracht10\LaravelTranslations\Drivers\Interface\Translatable;

class Translator implements Translatable
{
    function translate(string $text, string $targetLanguage): string
    {
        $tr = new GoogleTranslate();

        $tr->setSource();

        $tr->setTarget($targetLanguage);

        return $tr->translate($text);
    }
}
