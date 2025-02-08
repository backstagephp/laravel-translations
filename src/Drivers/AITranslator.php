<?php

namespace Backstage\Translations\Laravel\Drivers;

use Backstage\Translations\Laravel\Drivers\Interface\Translatable;
use EchoLabs\Prism\Prism;

class AITranslator implements Translatable
{
    public function translate(string $text, string $targetLanguage): string
    {
        $response = Prism::text()
            ->using(config('translations.translators.drivers.ai.provider'), config('translations.translators.drivers.ai.model'))
            ->withSystemPrompt(config('translations.translators.ai.system_prompt'))
            ->withPrompt('Translate the following text to '.$targetLanguage.': '.$text)
            ->generate();

        return $response->text;
    }
}
