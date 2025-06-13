<?php

namespace Backstage\Translations\Laravel\Models\Concerns;

use Backstage\Translations\Laravel\Facades\Translator;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Support\Facades\App;

trait HasTranslatableAttributes
{
    /**
     * Translate the given attributes.
     */
    public function translateAttributes(string $targetLanguage): array
    {
        $translatedAttributes = [];

        foreach ($this->getTranslatableAttributes() as $attribute) {
            $translatedAttributes[$attribute] = $this->translateAttribute($attribute, $targetLanguage);
        }

        return $translatedAttributes;
    }

    /**
     * Translate a single attribute.
     */
    public function translateAttribute(string $attribute, string $targetLanguage): string
    {
        $originalValue = $this->getAttribute($attribute);

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

    /**
     * Get the relationship for translated attributes.
     */
    public function translatableAttributes()
    {
        return $this->morphMany(TranslatedAttribute::class, 'translatable')->get();
    }
}
