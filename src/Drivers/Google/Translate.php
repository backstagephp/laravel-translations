<?php

namespace Vormkracht10\LaravelTranslations\Drivers\Google;

class GoogleClient
{
    public function __construct()
    {
        // Google API client setup
    }

    public function translate(string $text, string $targetLanguage): string
    {
        return 'Google translation';
    }
}