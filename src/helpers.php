<?php

function localized_country_name(string $code, ?string $locale = null): string
{
    $code = strtolower(explode('-', $code)[1] ?? $code);

    return Locale::getDisplayRegion('-'.$code, $locale ?? app()->getLocale());
}

function localized_language_name(string $code, ?string $locale = null): string
{
    $code = strtolower(explode('-', $code)[0]);

    return Locale::getDisplayLanguage($code, $locale ?? app()->getLocale());
}
