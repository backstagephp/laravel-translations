<?php

namespace Backstage\Translations\Laravel\Drivers\Interface;

interface Translatable
{
    public function translate(string $text, string $targetLanguage): string;
}
