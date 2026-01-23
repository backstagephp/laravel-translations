<?php

namespace Backstage\Translations\Laravel\Domain\Detection\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Stichoza\GoogleTranslate\GoogleTranslate;

class DetectLanguageFromText
{
    use AsAction;

    public function handle($text): string
    {
        $tr = new GoogleTranslate('fr', null);

        $translated = $tr->translate($text);

        $detected = $tr->getLastDetectedSource();

        if (! $detected) {
            throw new \Exception('Language detection failed for text: ' . $text);
        }

        return $detected;
    }
}
