<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translations';

    const STATUS_SAVED = 0;

    const STATUS_CHANGED = 1;

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

    public function getLanguageCodeAttribute()
    {
        return explode('_', $this->attributes['code'])[0];
    }

    public function getCountryCodeAttribute()
    {
        return explode('_', $this->attributes['code'])[1];
    }
}
