<?php

namespace Backstage\Translations\Laravel\Interfaces;

interface TranslatesAttributes
{
    /**
     * Translate the given attributes.
     *
     * @param string $targetLanguage
     * @return array
     */
    public function translateAttributes(string $targetLanguage): array;

    /**
     * Translate a single attribute.
     *
     * @param string $attribute
     * @param string $targetLanguage
     * @return string
     */
    public function translateAttribute(string $attribute, string $targetLanguage): string;

    /**
     * Get the attributes that should be translated.
     *
     * @return array
     */
    public function getTranslatableAttributes(): array;
}
