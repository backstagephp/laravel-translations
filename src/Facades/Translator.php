<?php

namespace Backstage\Translations\Laravel\Facades;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use Illuminate\Support\Facades\Facade;

class Translator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TranslatorContract::class;
    }
}
