<?php

namespace Backstage\Translations\Laravel\Drivers;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use DeepL\DeepLClient;
use DeepL\TextResult;
use DeepL\TranslateTextOptions;

class DeepLTranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string
    {
        if (str($text)->isUlid()) {
            return $text;
        }

        $deeplClient = static::initializeDeepLClient();

        $targetLanguage = static::normalizeLanguageCode($targetLanguage);

        $result = $deeplClient->translateText($text, null, $targetLanguage, [
            TranslateTextOptions::PRESERVE_FORMATTING => true,
        ]);

        return static::parseText($result);
    }

    protected static function normalizeLanguageCode(string $language): string
    {
        return match (strtolower($language)) {
            'en' => 'EN-GB',
            'pt' => 'PT-PT',
            'zh' => 'ZH-HANS',
            'es' => 'ES',
            default => strtoupper($language),
        };
    }

    protected static function initializeDeepLClient(): DeepLClient
    {
        $authkey = config('services.deepl.auth_key');

        if (! $authkey) {
            throw new \RuntimeException('DeepL auth key is not set in the configuration.');
        }

        $client = new DeepLClient($authkey, config('translations.translators.drivers.deep-l.options', []));

        return $client;
    }

    protected static function parseText(TextResult $textResult)
    {
        return $textResult->text;
    }
}
