<?php

namespace Backstage\Translations\Laravel\Models;

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
        'metadata',
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
    
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function getLanguageCodeAttribute()
    {
        return explode('_', $this->attributes['code'])[0];
    }

    public function getCountryCodeAttribute()
    {
        return explode('_', $this->attributes['code'])[1];
    }
}
