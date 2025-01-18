<?php

namespace Vormkracht10\LaravelTranslations\Drivers\Interface;

interface Translatable
{
    public function translate(string $text, string $targetLanguage): string;
}
