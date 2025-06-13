<?php

namespace Backstage\Translations\Laravel\Models\Concerns;

use Backstage\Translations\Laravel\Facades\Translator;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

trait HasTranslatableAttributes
{
    /**
     * Translate all configured translatable attributes into a given language.
     */
    public function translateAttributes(?string $targetLocale): array
    {
        $locale = $targetLocale ?: App::getLocale();

        return collect($this->getTranslatableAttributes())
            ->mapWithKeys(function (string $attribute) use ($locale) {
                return [$attribute => $this->translateAttribute($attribute, $locale)];
            })
            ->toArray();
    }

    /**
     * Translate all translatable attributes into every supported language.
     */
    public function translateAttributesForAllLanguages(): array
    {
        return Language::all()
            ->mapWithKeys(function (Language $language) {
                return [$language->code => $this->translateAttributes($language->code)];
            })
            ->toArray();
    }

    /**
     * Translate a specific attribute into every supported language.
     */
    public function translateAttributeForAllLanguages(string $attribute): array
    {
        return Language::all()
            ->mapWithKeys(function (Language $language) use ($attribute) {
                return [$language->code => $this->translateAttribute($attribute, $language->code)];
            })
            ->toArray();
    }

    /**
     * Translate a single attribute into a specific language.
     */
    public function translateAttribute(string $attribute, string $locale): string
    {
        $originalValue = $this->getAttribute($attribute);

        $translatedValue = Translator::with(config('translations.translators.default'))
            ->translate($originalValue, $locale);

        if ($translatedValue === null) {
            $translatedValue = $this->translatableAttributes()
                ->where('attribute', $attribute)
                ->where('code', $locale)
                ->value('translated_attribute');
        }

        if ($translatedValue !== null) {
            $this->storeTranslatedAttribute($attribute, $translatedValue, $locale);
        }

        return $translatedValue ?? $originalValue;
    }

    /**
     * Store or update a translated value for a given attribute and locale.
     */
    public function storeTranslatedAttribute(string $attribute, string $translatedText, string $locale): void
    {
        $this->translatableAttributes()->updateOrCreate([
            'translatable_type' => static::class,
            'translatable_id'   => $this->getKey(),
            'attribute'         => $attribute,
            'code'              => $locale,
        ], [
            'translated_attribute' => $translatedText,
            'translated_at'        => now(),
        ]);
    }

    /**
     * Get the translated value for a single attribute.
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale): ?string
    {
        if (! $this->isTranslatableAttribute($attribute)) {
            return $this->getAttribute($attribute);
        }

        $locale = $locale ?: App::getLocale();

        return $this->translatableAttributes()
            ->where('attribute', $attribute)
            ->where('code', $locale)
            ->first()
            ?->translated_attribute ?? $this->getAttribute($attribute);
    }

    /**
     * Get the list of attribute names that are translatable.
     */
    public function getTranslatableAttributes(): array
    {
        return [];
    }

    /**
     * Determine if an attribute is configured to be translatable.
     */
    public function isTranslatableAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getTranslatableAttributes());
    }

    /**
     * Define the morphMany relationship to translated attributes.
     */
    public function translatableAttributes(): MorphMany
    {
        return $this->morphMany(TranslatedAttribute::class, 'translatable');
    }
}
