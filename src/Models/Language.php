<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Observers\LanguageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Locale;

#[ObservedBy(LanguageObserver::class)]
class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    protected $primaryKey = 'code';

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'native' => 'string',
        'active' => 'boolean',
        'default' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public static function default(): ?Language
    {
        return static::firstWhere('default', true);
    }

    protected static function newFactory()
    {
        $package = Str::before(get_called_class(), 'Models\\');
        $modelName = Str::after(get_called_class(), 'Models\\');
        $path = $package.'Database\\Factories\\'.$modelName.'Factory';

        return $path::new();
    }

    public function translatableAttributes(): HasMany
    {
        return $this->hasMany(TranslatedAttribute::class, 'locale', 'code');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class, 'code', 'code');
    }

    public function languageRules(): HasMany
    {
        return $this->hasMany(LanguageRule::class, 'code', 'code');
    }

    public function getLanguageCodeAttribute()
    {
        return explode('-', $this->attributes['code'])[0];
    }

    public function getCountryCodeAttribute()
    {
        return explode('-', $this->attributes['code'])[1];
    }

    public function getTextualRulesQuery(): string
    {
        $baseRules = <<<'HTML'
            <translation-rules-query-base-rules>
                Important: Always treat singular, plural, diminutives, and common English equivalents as identical before translating. 
                For example: 'Worst', 'worstjes', 'Sausage', 'Sausages' are all equivalent. 
                Also: 'Huis', 'Huisje', 'House' are all equivalent. 
                And: 'Kaas', 'Kaasje', 'Cheese' are all equivalent. 
                
                Apply all translation rules after this normalization. 
                If a rule states a text *must not* match or translate to something, avoid that. 
                If it *must* match or translate to something, enforce that. 
                Multiple values follow the same logic. 
                If a translation *must be* a certain text, it cannot equal the source.
            </translation-rules-query-base-rules>
        HTML;

        $rules = $this->load('languageRules')->languageRules->map(function (LanguageRule $languageRule) {
            return '<translation-rules-query>'.$languageRule->getTextualQuery().'</translation-rules-query>';
        })->filter()->implode("\n");

        return $baseRules."\n\n".$rules;
    }

    public function getLocalizedCountryNameAttribute($locale = null)
    {
        $code = strtolower(explode('-', $this->attributes['code'])[1] ?? $this->attributes['code']);

        return Locale::getDisplayRegion('-'.$code, $locale ?? app()->getLocale());
    }

    public function getLocalizedLanguageNameAttribute($locale = null)
    {
        $code = strtolower(explode('-', $this->attributes['code'])[0]);

        return Locale::getDisplayLanguage($code, $locale ?? app()->getLocale());
    }
}
