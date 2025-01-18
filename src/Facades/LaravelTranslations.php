<?php

namespace Vormkracht10\LaravelTranslations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vormkracht10\LaravelTranslations\LaravelTranslations
 */
class LaravelTranslations extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Vormkracht10\LaravelTranslations\LaravelTranslations::class;
    }
}
