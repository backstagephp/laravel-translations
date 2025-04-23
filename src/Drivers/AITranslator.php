<?php

namespace Backstage\Translations\Laravel\Drivers;

use Prism\Prism\Prism;
use Backstage\Translations\Laravel\Contracts\TranslatorContract;

class AITranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string
    {
        $response = Prism::text()
            ->using(config('translations.translators.drivers.ai.provider'), config('translations.translators.drivers.ai.model'))
            ->withSystemPrompt(config('translations.translators.drivers.ai.system_prompt'))
            ->withPrompt('Translate the following text to ' . $targetLanguage . ': ' . $text)
            ->asText();

        return $response->text;
    }
}
