<?php

namespace Backstage\Translations\Laravel\Managers;

use Backstage\Translations\Laravel\Drivers\AITranslator;
use Backstage\Translations\Laravel\Drivers\DeepLTranslator;
use Backstage\Translations\Laravel\Drivers\GoogleTranslator;
use Illuminate\Support\Manager;

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

    protected function createAiDriver()
    {
        return new AITranslator;
    }

    protected function createDeepLDriver()
    {
        return new DeepLTranslator;
    }

    public function getDefaultDriver(): string
    {
        return config('translations.translators.default');
    }
}
