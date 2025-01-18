<?php

namespace Vormkracht10\LaravelTranslations\Drivers\OpenAI;

use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use Vormkracht10\LaravelTranslations\Drivers\Interface\Translatable;

class Translator implements Translatable
{
    public function translate(string $text, string $targetLanguage): string
    {
        $response = Prism::text()
            ->using(Provider::OpenAI, 'gpt-4o-mini')
            ->withSystemPrompt('You are an expert mathematician who explains concepts simply. The only thing you do it output what i ask. No comments, no extra information. Just the answer.')
            ->withPrompt('Translate the following text to '.$targetLanguage.': '.$text)
            ->generate();

        return $response->text;
    }
}
