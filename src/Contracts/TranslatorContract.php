<?php

namespace Backstage\Translations\Laravel\Contracts;

interface TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string|array;
}
