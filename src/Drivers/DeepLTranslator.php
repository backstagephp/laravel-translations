<?php

namespace Backstage\Translations\Laravel\Drivers;

use DeepL\DeepLClient;
use Backstage\Translations\Laravel\Contracts\TranslatorContract;

class DeepLTranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string|array
    {
        $authkey = config('services.deepl.auth_key');

        $deeplClient = new DeepLClient($authkey, config('translations.translators.drivers.deep-l.options', []));

        $result = $deeplClient->translateText($text, null, $targetLanguage);

        return $result->text;
    }
}
