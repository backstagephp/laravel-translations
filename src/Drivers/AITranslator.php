<?php

namespace Backstage\Translations\Laravel\Drivers;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use Prism\Prism\Prism;

class AITranslator implements TranslatorContract
{
    public function translate(string $text, string $targetLanguage): string
    {
        $instructions = [
            "Translate the following text to {$targetLanguage}.",
            "Preserve the original format, including any special characters.",
            "Do not alter the meaning of the text.",
            "Use correct grammar and punctuation appropriate for the target language.",
            "Maintain the intent of the sentence (question, command, or statement).",
            "Return *only* the translated text—no additional commentary or output.",
            "No unnecessary language or elaboration.",
            "Text to translate: {$text}",
        ];
        
        $instructionsString = implode("\n", $instructions);
        
        $response = Prism::text()
            ->using(config('translations.translators.drivers.ai.provider'), config('translations.translators.drivers.ai.model'))
            ->withSystemPrompt(config('translations.translators.drivers.ai.system_prompt'))
            ->withPrompt($instructionsString)
            ->asText();

        return $response->text;
    }
}
