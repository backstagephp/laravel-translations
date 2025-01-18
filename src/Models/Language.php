<?php

namespace Vormkracht10\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'locale',
        'label',
    ];

    protected $casts = [
        'locale' => 'string',
        'label' => 'string',
    ];
}
