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
     * Translate the given attributes.
     */
    public function translateAttributes(?string $targetLanguage): array
    {
        $targetLanguage = $targetLanguage ?: App::getLocale();

        $translatedAttributes = [];

        foreach ($this->getTranslatableAttributes() as $attribute) {
            $translatedAttributes[$attribute] = $this->translateAttribute($attribute, $targetLanguage);
        }

        return $translatedAttributes;
    }

    /**
     * Translate attributes for all languages defined in the system.
     */
    public function translateAttributesForAllLanguages(): array
    {
        $languages = Language::all();

        $translations = $languages->mapWithKeys(function (Language $language) {
            return [$language->code => $this->translateAttributes($language->code)];
        });

        return $translations->toArray();
    }

    public function translateAttributeForAllLanguages(string $attribute): array
    {
        $languages = Language::all();

        $translations = $languages->mapWithKeys(function (Language $language) use ($attribute) {
            return [$language->code => $this->translateAttribute($attribute, $language->code)];
        });

        return $translations->toArray();
    }

    /**
     * Translate a single attribute.
     */
    public function translateAttribute(string $attribute, string $targetLanguage): string
    {
        $originalValue = $this->getAttribute($attribute);

        $isTranslated = $this->translatableAttributes()
            ->getQuery()
            ->where('attribute', $attribute)
            ->where('code', $targetLanguage)
            ->exists();

        if ($isTranslated) {
            return $this->getTranslatedAttribute($attribute, $targetLanguage);
        }

        $translated = Translator::with(config('translations.translators.default'))
            ->translate($originalValue, $targetLanguage);

        if ($translated === null) {
            $translated = $this->translatableAttributes()
                ->where('attribute', $attribute)
                ->where('code', $targetLanguage)
                ->value('translated_attribute');
        }

        $this->pushTranslateAttribute($attribute, $translated, $targetLanguage);

        return $translated ?: $originalValue;
    }

    /**
     * Store or update a translated attribute.
     */
    public function pushTranslateAttribute(string $attribute, string $translation, string $locale): void
    {
        $this->translatableAttributes()->updateOrCreate([
            'translatable_type' => static::class,
            'translatable_id' => $this->getKey(),
            'attribute' => $attribute,
            'code' => $locale,
        ], [
            'translated_attribute' => $translation,
            'translated_at' => now(),
        ]);
    }

    /**
     * Get the translated value of a given attribute.
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale): ?string
    {
        if (! in_array($attribute, $this->getTranslatableAttributes())) {
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
     * Get the attributes that should be translated.
     */
    public function getTranslatableAttributes(): array
    {
        return [];
    }

    public function isTranslatableAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getTranslatableAttributes());
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
        $designatedAttributes = $this->getTranslatableAttributes();

        foreach ($designatedAttributes as $attribute) {
            $this->translateAttributeForAllLanguages($attribute);
        }
    }
}
