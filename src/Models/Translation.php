<?php

namespace Vormkracht10\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translations';

    protected $fillable = [
        'locale',
        'group',
        'key',
        'text',
        'metadata',
        'namespace',
        'last_scanned_at',
        'translated_at',
    ];

    protected $casts = [
        'locale' => 'string',
        'group' => 'string',
        'key' => 'string',
        'text' => 'string',
        'namespace' => 'string',
        'last_scanned_at' => 'datetime',
        'translated_at' => 'datetime',
    ];
}
