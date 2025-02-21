<?php

namespace Backstage\Translations\Laravel\Managers;

use Illuminate\Support\Manager;
use Backstage\Translations\Laravel\Drivers\AITranslator;
use Backstage\Translations\Laravel\Drivers\GoogleTranslator;

class TranslatorManager extends Manager
{
    protected string $driver;

    public function with(string $driver)
    {
        $this->driver = $driver;

        return $this->driver($driver);
    }

    protected function createGoogleTranslateDriver()
    {
        return new GoogleTranslator;
    }

    protected function createAITranslatorDriver()
    {
        return new AITranslator;
    }

    public function getDefaultDriver(): string
    {
        return config('translations.translators.default');
    }
}
