<?php

namespace Backstage\Translations\Laravel\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface TranslatesAttributes
{
    /**
     * Translate the given attributes.
     */
    public function translateAttributes(?string $targetLanguage): array;

    /**
     * Translate attributes for all languages defined in the system.
     */
    public function translateAttributesForAllLanguages(): array;

    /**
     * Translate a single attribute for all languages.
     */
    public function translateAttributeForAllLanguages(string $attribute): array;

    /**
     * Translate a single attribute.
     */
    public function translateAttribute(string $attribute, string $targetLanguage): string;

    /**
     * Store or update a translated attribute.
     */
    public function pushTranslateAttribute(string $attribute, string $translation, string $locale): void;

    /**
     * Get the translated value of a given attribute.
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale): ?string;

    /**
     * Get the attributes that should be translated.
     */
    public function getTranslatableAttributes(): array;

    /**
     * Determine if an attribute is translatable.
     */
    public function isTranslatableAttribute(string $attribute): bool;

    /**
     * Get the relationship for translated attributes.
     */
    public function translatableAttributes(): MorphMany;

    /**
     * Sync translations (implementation-specific).
     */
    public function syncTranslations(): void;
}
