<?php

namespace Vormkracht10\LaravelTranslations\Drivers\OpenAI;

use Vormkracht10\LaravelTranslations\Drivers\Interface\Translatable;

class Translator implements Translatable
{
    public OpenAIClient $client;

    public function __construct()
    {
        $this->client = new OpenAIClient;
    }

    public function translate(string $text, string $targetLanguage): string
    {
        return $this->client->translate($text, $targetLanguage);
    }
}
