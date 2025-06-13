<?php

namespace Backstage\Translations\Laravel\Interfaces;

interface TranslatesAttributes
{
    /**
     * Translate the given attributes.
     */
    public function translateAttributes(string $targetLanguage): array;

    /**
     * Translate a single attribute.
     */
    public function translateAttribute(string $attribute, string $targetLanguage): string;

    /**
     * Get the attributes that should be translated.
     */
    public function getTranslatableAttributes(): array;
}
