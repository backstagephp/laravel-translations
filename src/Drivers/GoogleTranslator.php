<?php

namespace Backstage\Translations\Laravel\Drivers;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use Backstage\Translations\Laravel\Drivers\Interface\Translatable;
use Stichoza\GoogleTranslate\GoogleTranslate;

class GoogleTranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string
    {
        $tr = new GoogleTranslate;

        $tr->setSource();

        $tr->setTarget($targetLanguage);

        return retry(3, fn () => $tr->translate($text), 100);
    }
}
