<?php

namespace Backstage\Translations\Laravel\Models\Concerns;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\GetTranslatedAttribute;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\GetTranslatedAttributes;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\IsTranslatableAttribute;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\PushTranslatedAttribute;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\SyncTranslations;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttribute;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributeForAllLanguages;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributes;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\TranslateAttributesForAllLanguages;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\UpdateAttributesIfTranslatable;
use Backstage\Translations\Laravel\Domain\Translatables\Actions\UpdateTranslateAttributes;
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
            dispatch(fn () => $model->syncTranslations());
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

            dispatch(fn () => $model->updateAttributesIfTranslatable($translatableAttributes));
        });

        /**
         * @param  self  $model
         */
        static::deleting(function (TranslatesAttributes $model) {
            dispatch(fn () => $model->translatableAttributes->each->delete());
        });
    }

    /**
     * Translate the given attributes.
     */
    public function translateAttributes(?string $targetLanguage = null): array
    {
        return TranslateAttributes::run(
            model: $this,
            targetLanguage: $targetLanguage ?? app()->getLocale()
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
    public function translateAttribute(mixed $attribute, string $targetLanguage, bool $overwrite = false, ?string $extraPrompt = null): mixed
    {
        return TranslateAttribute::run(
            model: $this,
            attribute: $attribute,
            targetLanguage: $targetLanguage,
            overwrite: $overwrite,
            extraPrompt: $extraPrompt
        );
    }

    /**
     * Store or update a translated attribute.
     */
    public function pushTranslateAttribute(string $attribute, mixed $translation, string $locale): void
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
    public function getTranslatedAttribute(string $attribute, ?string $locale = null): mixed
    {
        return GetTranslatedAttribute::run(
            model: $this,
            attribute: $attribute,
            locale: $locale ?? app()->getLocale()
        );
    }

    public function getTranslatedAttributes(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        return GetTranslatedAttributes::run(
            model: $this,
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

    public function syncTranslations(?\Illuminate\Console\OutputStyle $output = null): void
    {
        SyncTranslations::run(
            model: $this,
            output: $output
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
        UpdateAttributesIfTranslatable::run(
            model: $this,
            translatableAttributes: $translatableAttributes
        );
    }

    public function getTranslatableAttributeRulesFor(string $attribute): array|string
    {
        $methodName = 'getTranslatableAttributeRulesFor'.str($attribute)->studly();

        if (! method_exists($this, $methodName)) {
            return '*';
        }

        return $this->{$methodName}();
    }
}
