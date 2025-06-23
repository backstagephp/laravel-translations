<?php

namespace Backstage\Translations\Laravel\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @contract TranslatesAttributes
 *
 * Provides translation capabilities to model attributes, supporting
 * translation storage, retrieval, and synchronization across locales.
 */
interface TranslatesAttributes
{
    /**
     * Translate the given attributes to a target language.
     *
     * @param  string|null  $targetLanguage  ISO 639-1 code or null for default language.
     * @return array<string, string> Associative array of translated attributes.
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\TranslateAttribute
     */
    public function translateAttributes(?string $targetLanguage): array;

    /**
     * Translate all translatable attributes to all available languages.
     *
     * @return array<string, array<string, string>> Attribute name => [locale => translation].
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\TranslateAttributesForAllLanguages
     */
    public function translateAttributesForAllLanguages(): array;

    /**
     * Translate a specific attribute to all available languages.
     *
     * @param  string  $attribute  The attribute to translate.
     * @return array<string, string> Translations keyed by locale.
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\TranslateAttributeForAllLanguages
     */
    public function translateAttributeForAllLanguages(string $attribute): array;

    /**
     * Translate a specific attribute to a target language.
     *
     * @param  string  $attribute  The attribute to translate.
     * @param  string  $targetLanguage  ISO 639-1 code.
     * @return string The translated value.
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\TranslateAttribute
     */
    public function translateAttribute(string $attribute, string $targetLanguage): string;

    /**
     * Persist a translation for a given attribute and locale.
     *
     * @param  string  $attribute  The attribute name.
     * @param  string  $translation  The translated value.
     * @param  string  $locale  The locale code (ISO 639-1).
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\PushTranslatedAttribute
     */
    public function pushTranslateAttribute(string $attribute, string $translation, string $locale): void;

    /**
     * Retrieve the translation of a specific attribute for a locale.
     *
     * @param  string  $attribute  The attribute name.
     * @param  string|null  $locale  Locale to fetch translation for, or null for fallback/default.
     * @return string|null The translated value or null if not found.
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\GetTranslatedAttribute
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale): ?string;

    /**
     * Return the list of attributes marked as translatable.
     *
     * @return array<int, string> List of attribute names.
     */
    public function getTranslatableAttributes(): array;

    /**
     * Check if an attribute is translatable.
     *
     * @param  string  $attribute  Attribute to check.
     * @return bool True if translatable, false otherwise.
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\IsTranslatableAttribute
     */
    public function isTranslatableAttribute(string $attribute): bool;

    /**
     * MorphMany relationship with the translated attributes table.
     *
     * @return MorphMany Eloquent relationship.
     */
    public function translatableAttributes(): MorphMany;

    /**
     * Sync translations, typically after creation or update.
     *
     *
     * @see \Backstage\Translations\Laravel\Actions\Translatables\SyncTranslations
     */
    public function syncTranslations(): void;
}
