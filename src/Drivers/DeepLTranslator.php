<?php

namespace Backstage\Translations\Laravel\Drivers;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use DeepL\DeepLClient;
use Locale;

class DeepLTranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string|array
    {
        $authkey = config('services.deepl.auth_key');

        $deeplClient = new DeepLClient($authkey, config('translations.translators.drivers.deep-l.options', []));

        $targetLanguage = static::normalizeLanguageCode($targetLanguage);

        $result = $deeplClient->translateText($text, null, $targetLanguage);

        return $result->text;
    }

    protected static function normalizeLanguageCode(string $lang): string
    {
        $canonical = Locale::canonicalize($lang);

        $language = Locale::getPrimaryLanguage($canonical);

        $region = Locale::getRegion($canonical);

        if (! $region) {
            if ($language === 'en') {
                $region = 'GB';
            } elseif ($language === 'pt') {
                $region = 'PT';
            } else {
                $region = strtoupper($language);
            }
        }

        return Locale::composeLocale([
            'language' => $language,
            'region' => $region,
        ]);
    }
}
