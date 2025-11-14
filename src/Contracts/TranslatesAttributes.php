<?php

namespace Backstage\Translations\Laravel\Contracts;

use Illuminate\Console\OutputStyle;
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
     * @param  string|null  $targetLanguage  Langauge code or null for default language.
     * @return array<string, string> Associative array of translated attributes.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributes
     */
    public function translateAttributes(?string $targetLanguage = null): array;

    /**
     * Translate all translatable attributes to all available languages.
     *
     * @return array<string, array<string, string>> Attribute name => [locale => translation].
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributesForAllLanguages
     */
    public function translateAttributesForAllLanguages(): array;

    /**
     * Translate a specific attribute to all available languages.
     *
     * @param  string  $attribute  The attribute to translate.
     * @param  bool  $overwrite  Whether to overwrite existing translations.
     * @return array<string, string> Translations keyed by locale.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributeForAllLanguages
     */
    public function translateAttributeForAllLanguages(string $attribute, bool $overwrite = false): array;

    /**
     * Translate a specific attribute to a target language.
     *
     * @param  string  $attribute  The attribute to translate.
     * @param  string  $targetLanguage  ISO 639-1 code.
     * @param  bool  $overwrite  Whether to overwrite existing translation.
     * @return mixed The translated value.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttribute
     */
    public function translateAttribute(mixed $attribute, string $targetLanguage, bool $overwrite = false): mixed;

    /**
     * Persist a translation for a given attribute and locale.
     *
     * @param  string  $attribute  The attribute name.
     * @param  string  $translation  The translated value.
     * @param  string  $locale  The locale code (ISO 639-1).
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\PushTranslatedAttribute
     */
    public function pushTranslateAttribute(string $attribute, string $translation, string $locale): void;

    /**
     * Retrieve the translation of a specific attribute for a locale.
     *
     * @param  string  $attribute  The attribute name.
     * @param  string|null  $locale  Locale to fetch translation for, or null for fallback/default.
     * @return mixed The translated value or null if not found.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\GetTranslatedAttribute
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale = null): mixed;

    /**
     * Get all translated attributes for the model in a specific locale.
     *
     * @param  string|null  $locale  Locale to fetch translations for, or null for default.
     * @return array<string, mixed> Associative array of translated attributes.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\GetTranslatedAttributes
     */
    public function getTranslatedAttributes(?string $locale = null): array;

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
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\IsTranslatableAttribute
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
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\SyncTranslations
     */
    public function syncTranslations(?OutputStyle $output = null): void;

    /**
     * Update multiple translate attributes.
     *
     * @param  array<string, mixed>  $attributes  Attributes to update.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\UpdateTranslateAttributes
     */
    public function updateTranslateAttributes(array $attributes): void;

    /**
     * Update only attributes that are translatable.
     *
     * @param  array<int, string>  $translatableAttributes  Attribute names to update.
     *
     * @see \Backstage\Translations\Laravel\Domain\Translatables\Actions\UpdateAttributesIfTranslatable
     */
    public function updateAttributesIfTranslatable(array $translatableAttributes): void;

    /**
     * Get the translation rules for a specific attribute.
     *
     * @param  string  $attribute  Attribute name.
     * @return array|string Rules array or '*' string for all.
     */
    public function getTranslatableAttributeRulesFor(string $attribute): array|string;
}
