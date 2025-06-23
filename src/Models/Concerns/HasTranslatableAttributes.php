<?php

namespace Backstage\Translations\Laravel\Models\Concerns;

use Backstage\Translations\Laravel\Actions\Translatables\GetTranslatedAttribute;
use Backstage\Translations\Laravel\Actions\Translatables\IsTranslatableAttribute;
use Backstage\Translations\Laravel\Actions\Translatables\PushTranslatedAttribute;
use Backstage\Translations\Laravel\Actions\Translatables\SyncTranslations;
use Backstage\Translations\Laravel\Actions\Translatables\TranslateAttribute;
use Backstage\Translations\Laravel\Actions\Translatables\TranslateAttributeForAllLanguages;
use Backstage\Translations\Laravel\Actions\Translatables\TranslateAttributesForAllLanguages;
use Backstage\Translations\Laravel\Actions\Translatables\UpdateTranslateAttributes;
use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslatableAttributes
{
    /**
     * Boot the trait.
     */
    public static function bootHasTranslatableAttributes(): void
    {
        /**
         * @param  self  $model
         */
        static::created(function (TranslatesAttributes $model) {
            $model->syncTranslations();
        });

        /**
         * @param  self  $model
         */
        static::updated(function (TranslatesAttributes $model) {
            $dirtyAttributes = $model->getDirty();

            $translatableAttributes = array_intersect(
                array_keys($dirtyAttributes),
                $model->getTranslatableAttributes()
            );

            $model->updateAttributesIfTranslatable($translatableAttributes);
        });

        /**
         * @param  self  $model
         */
        static::deleting(function (TranslatesAttributes $model) {
            $model->translatableAttributes->each->forceDelete();
        });
    }

    /**
     * Translate the given attributes.
     */
    public function translateAttributes(?string $targetLanguage): array
    {
        return TranslateAttribute::run(
            model: $this,
            targetLanguage: $targetLanguage,
        );
    }

    /**
     * Translate attributes for all languages defined in the system.
     */
    public function translateAttributesForAllLanguages(): array
    {
        return TranslateAttributesForAllLanguages::run(
            model: $this
        );
    }

    public function translateAttributeForAllLanguages(string $attribute, bool $overwrite = false): array
    {
        return TranslateAttributeForAllLanguages::run(
            model: $this,
            attribute: $attribute,
            overwrite: $overwrite
        );
    }

    /**
     * Translate a single attribute.
     */
    public function translateAttribute(string $attribute, string $targetLanguage, bool $overwrite = false): string
    {
        return TranslateAttribute::run(
            model: $this,
            attribute: $attribute,
            targetLanguage: $targetLanguage,
            overwrite: $overwrite
        );
    }

    /**
     * Store or update a translated attribute.
     */
    public function pushTranslateAttribute(string $attribute, string $translation, string $locale): void
    {
        PushTranslatedAttribute::run(
            model: $this,
            attribute: $attribute,
            translation: $translation,
            locale: $locale
        );
    }

    /**
     * Get the translated value of a given attribute.
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale): ?string
    {
        return GetTranslatedAttribute::run(
            model: $this,
            attribute: $attribute,
            locale: $locale
        );
    }

    /**
     * Get the attributes that should be translated.
     */
    public function getTranslatableAttributes(): array
    {
        return [];
    }

    public function isTranslatableAttribute(string $attribute): bool
    {
        return IsTranslatableAttribute::run(
            model: $this,
            attribute: $attribute
        );
    }

    /**
     * Get the relationship for translated attributes.
     */
    public function translatableAttributes(): MorphMany
    {
        return $this->morphMany(TranslatedAttribute::class, 'translatable');
    }

    public function syncTranslations(): void
    {
        SyncTranslations::run(
            model: $this,
        );
    }

    public function updateTranslateAttributes(array $attributes): void
    {
        UpdateTranslateAttributes::run(
            model: $this,
            attributes: $attributes
        );
    }

    public function updateAttributesIfTranslatable(array $translatableAttributes): void
    {
        UpdateTranslateAttributes::run(
            model: $this,
            attributes: $translatableAttributes
        );
    }
}
