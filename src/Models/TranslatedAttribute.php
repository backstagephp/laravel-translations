<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslatedAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'translatable_type',
        'translatable_id',
        'attribute',
        'translated_attribute',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function translatable()
    {
        return $this->morphTo();
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'code', 'code');
    }
}
