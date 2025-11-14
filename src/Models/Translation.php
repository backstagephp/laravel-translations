<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Caches\TranslationStringsCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translation extends Model
{
    protected $table = 'translations';

    protected $fillable = [
        'code',
        'group',
        'key',
        'text',
        'source_text',
        'namespace',
        'translated_at',
    ];

    protected $casts = [
        'code' => 'string',
        'group' => 'string',
        'key' => 'string',
        'text' => 'string',
        'namespace' => 'string',
        'translated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Translation $translation) {
            dispatch(fn () => TranslationStringsCache::update());
        });
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function getLanguageCodeAttribute()
    {
        return explode('-', $this->attributes['code'])[0];
    }

    public function getCountryCodeAttribute()
    {
        return explode('-', $this->attributes['code'])[1];
    }
}
