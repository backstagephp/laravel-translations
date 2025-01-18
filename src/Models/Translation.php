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
    ];

    protected $casts = [
        'locale' => 'string',
        'group' => 'string',
        'key' => 'string',
        'text' => 'string',
        'namespace' => 'string',
    ];
}
